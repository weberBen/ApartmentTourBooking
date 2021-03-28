import React, { createRef, useEffect, useState, useRef} from "react";
import { StyleSheet, Text, View, Button, Alert, TextInput, Modal, CheckBox, ActivityIndicator } from "react-native";
import SwipeCards from "react-native-swipe-cards-deck";
import ServerRoutes from '../src/ServerRoutes';
import axios from '../src/AxiosInstance';
import * as SMS from 'expo-sms';
import * as UserStorage from '../src/UserStorage';
import * as Calendar from 'expo-calendar';
import moment from 'moment';
import { IconButton, Colors } from 'react-native-paper';
import Clipboard from 'expo-clipboard';


function makeid(length) {
    var result           = '';
    var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    for ( var i = 0; i < length; i++ ) {
       result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
 }

 function generateNewPassword()
 {
     return makeid(5);
 }

 function getMsg(model, server_url, pwd, login_url="")
 {
    model = model.replace('{{server.url}}', server_url);
    model = model.replace('{{user.unencrypted_pwd}}', pwd);
    model = model.replace('{{user.login_url}}', login_url);

    return model;
 }

function App({ }) {

    const [user_data, setUserData] = useState(null);
    const [send_sms, setSendSms] = useState(true);
    const [model_sms_msg, setModelSmsMsg] = useState('');
    const [phone_number, setPhoneNumber] = useState('');
    const [name, setName] = useState('none');
    const [pwd, setPwd] = useState(generateNewPassword());
    const [prev_pwd, setPrevPwd] = useState(null);
    const [processing, setProcessing] = useState(false);

    function registerUser(name, phone_number, password, msg_model, before_contact_name)
    {
        setProcessing(true);
        axios.post(ServerRoutes.api + "/auth/register", null, {
            params: {
                name: name,
                phone: phone_number,
                password: password,
                with_login_url: true,
            }
            }).then((response) => {
                const data = response.data;

                setProcessing(false);

                if("error" in data)
                {
                    console.error(data);
                    Alert.alert(
                    "Error",
                    JSON.stringify(data),
                    [
                        { text: "OK" }
                    ]);

                    return;
                }

                console.log("data=");
                console.log(data);

                Clipboard.setString(before_contact_name + " " + data.data.id);

                if(send_sms)
                {

                    console.log("login=");
                    console.log(data.login_url);
                    console.log("model=");
                    console.log(msg_model);
                    SMS.sendSMSAsync(
                        [phone_number],
                        getMsg(msg_model, user_data.server.url, password, data.login_url),
                        {}
                      );
                }

                Alert.alert(
                "Status",
                "Data has been successfully saved",
                [
                    { text: "OK" }
                ]);
        
            }).catch((err) => { 
            
                console.error(err);

                setProcessing(false);

                Alert.alert(
                "Error",
                JSON.stringify(err),
                [
                    { text: "OK" }
                ]);
            });
        }


        useEffect(() => {
            UserStorage.retrieve().then((user_data) => {
                setUserData(user_data);

                axios.get(ServerRoutes.api + "/language/msg", {
                    params: {
                       language: user_data.language,
                    }
                    }).then((response) => {
                        const data = response.data;
                        
                        const msg = data.data;
                        const registration_model = msg.registration + msg.appendix_msg;

                        setModelSmsMsg(registration_model);

                    }).catch((err) => { 
                    
                        console.error(err);
        
                        Alert.alert(
                        "Error",
                        JSON.stringify(err),
                        [
                            { text: "OK" }
                        ]);
                    });
            });
          }, []);

    return (
      <View style={{ flex: 1, alignItems: 'center', justifyContent: 'center' }}>

            <View>
                <Text style={{fontWeight:"bold", paddingBottom:5}}> User info </Text>

                <Text style={{color:'grey', paddingTop:20}}> name </Text>
                <TextInput
                    style={{backgroundColor: "#ECE9E9", width:"100%"}}
                    placeholder="user name"
                    autoFocus={true}
                    onChangeText={text => setName(text)}
                    defaultValue={name}
                    />
                
                <Text style={{color:'grey', paddingTop:20}}> phone </Text>
                <TextInput
                    style={{backgroundColor: "#ECE9E9", width:"100%"}}
                    placeholder="phone number (international format"
                    autoFocus={true}
                    onChangeText={text => setPhoneNumber(text)}
                    defaultValue={phone_number}
                    autoCapitalize = 'none'
                />

                <Text style={{color:'grey', paddingTop:20}}> Password </Text>
                <View>
                    <TextInput
                        style={{backgroundColor: "#ECE9E9", width:"100%"}}
                        placeholder="password"
                        autoFocus={true}
                        onChangeText={text => setPwd(text)}
                        defaultValue={pwd}
                        autoCapitalize = 'none'
                        secureTextEntry={false}
                    />
                    <IconButton
                        icon="refresh"
                        color="black"
                        size={20}
                        onPress={() => {
                            const password = generateNewPassword();
                            setPwd(password);
                        }}
                    />
                </View>
                
            </View>

            <View style={{flexDirection: "row", paddingTop:10}}>
                <Text style={{}}>Send sms ?</Text>
                <CheckBox
                value={send_sms}
                onValueChange={setSendSms}
                style={{alignSelf: "center"}}
                />
            </View>

        <View style={{paddingBottom:40}}/>

        {processing && <ActivityIndicator size="large" color='red' /> }
        <Button onPress={() => {
                var password = null;

                if(prev_pwd==pwd)
                {
                    setPrevPwd(pwd);
                    password = generateNewPassword();
                    setPwd(password);
                }else
                {
                    setPrevPwd(pwd);
                    password = pwd;
                }

                registerUser(name, phone_number, password, model_sms_msg, user_data.formats.before_contact_name);
            }} 
            title="Register" 
        />
      </View>
    );
}

export default App;