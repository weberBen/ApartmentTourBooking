import React, { createRef, useEffect, useState, useRef} from "react";
import { StyleSheet, Text, View, Button, Alert, TextInput, Modal, CheckBox, ActivityIndicator, TouchableHighlight, ScrollView, FlatList } from "react-native";
import SwipeCards from "react-native-swipe-cards-deck";
import ServerRoutes from '../src/ServerRoutes';
import axios from '../src/AxiosInstance';
import * as SMS from 'expo-sms';
import * as UserStorage from '../src/UserStorage';
import * as Calendar from 'expo-calendar';
import moment from 'moment';
import { IconButton, Colors } from 'react-native-paper';
import DateTimePicker from '@react-native-community/datetimepicker';


const Item = ({ item }) => {
    return (
        <View style={{}}>
            <View style={{padding:10}}>
                <Text style={{fontWeight:'bold', color:'#d391b4'}}>Target Event :</Text>
                <Text>
                    <Text style={{fontWeight:'bold', color:'grey'}}>Start date :</Text>
                    <Text style={{color:'black'}}> {item.input_event.start_date} </Text> 
                </Text>
                <Text>
                    <Text style={{fontWeight:'bold', color:'grey'}}>End date :</Text>
                    <Text style={{color:'black'}}> {item.input_event.end_date} </Text> 
                </Text>
            </View>

            <View
                style={{
                marginRight:20,
                marginLeft:20,
                height: 1,
                width: "50%",
                backgroundColor: "#d391b4",
                }}
            />

            <View style={{padding:10}}>
                <Text style={{fontWeight:'bold', color:'#d391b4'}}>Overlapped Event :</Text>
                
                <Text>
                    <Text style={{fontWeight:'bold', color:'grey'}}>Id :</Text>
                    <Text style={{color:'black'}}> {item.overlapped_event.id} </Text> 
                </Text>
                <Text>
                    <Text style={{fontWeight:'bold', color:'grey'}}>Reference :</Text>
                    <Text style={{color:'black'}}> {item.overlapped_event.reference} </Text> 
                </Text>
                <Text>
                    <Text style={{fontWeight:'bold', color:'grey'}}>Start date :</Text>
                    <Text style={{color:'black'}}> {item.overlapped_event.start_date} </Text> 
                </Text>
                <Text>
                    <Text style={{fontWeight:'bold', color:'grey'}}>End date :</Text>
                    <Text style={{color:'black'}}> {item.overlapped_event.end_date} </Text> 
                </Text>
            </View>
        </View>
    );
}

const ItemSeparator = () => {
    return (
      <View
        style={{
          height: 1,
          width: "100%",
          backgroundColor: "#000",
        }}
      />
    );
  }

const App = () => {
  const [start_date, setStartDate] = useState(new Date());
  const [start_mode, setStartMode] = useState(null);
  const [start_show, setStartShow] = useState(false);

  const [end_date, setEndDate] = useState(new Date());
  const [end_mode, setEndMode] = useState(null);
  const [end_show, setEndShow] = useState(false);

  const [processing, setProcessing] = useState(false);
  const [timezone, setTimezone] = useState(null);

  const [period_length, setPeriodLength] = useState(15);
  const [period_sleep, setPeriodSleep] = useState(5);

  const [modalVisible, setModalVisible] = useState(false);
  const [invalid_events, setInvalidEvents] = useState([]);


  const onChangeStart = (event, selectedDate) => {
    const currentDate = selectedDate || start_date;
    setStartDate(currentDate);

    if(start_mode=='date')
    {
        setStartMode('time');
    }else
    {
        setStartShow(false);
    }
  };

  const showStartMode = (currentMode) => {
    setStartShow(true);
    setStartMode(currentMode);
  };

  const showStartDatepicker = () => {
    showStartMode('date');
  };


  const onChangeEnd = (event, selectedDate) => {
    const currentDate = selectedDate || end_date;
    setEndDate(currentDate);

    if(end_mode=='date')
    {
        setEndMode('time');
    }else
    {
        setEndShow(false);
    }
  };

  const showEndMode = (currentMode) => {
    setEndShow(true);
    setEndMode(currentMode);
  };

  const showEndDatepicker = () => {
    showEndMode('date');
  };

  useEffect(() => {
    axios.get(ServerRoutes.api + "/info", {
        params: {
        }
        }).then((response) => {
            const data = response.data;
            
            setTimezone(data.data.timezone.value);
    
        }).catch((error) => { 
            console.error(error);
  
            Alert.alert(
            "Error",
            JSON.stringify(error),
            [
                { text: "OK" }
            ]);
        });

    }, []);

    function addPeriod(start_date, end_date, period_length, period_sleep)
    {

        if(processing)
            return; 
        
        setInvalidEvents([]);
        setProcessing(true);

        axios.get(ServerRoutes.api + "/calendar/addUnallocatedPeriod", {
            params: {
                period_start: moment(start_date).format('YYYY/MM/DD HH:mm'),
                period_end: moment(end_date).format('YYYY/MM/DD HH:mm'),
                period_length_min: period_length,
                period_delay_min: period_sleep,
            }
            }).then((response) => {
                const data = response.data;

                setProcessing(false);

                if("error" in data)
                {
                    if("invalid_events" in data)
                    {
                        setInvalidEvents(data.invalid_events);
                        setModalVisible(true);

                    }else
                    {
                        Alert.alert(
                        "Error",
                        JSON.stringify(data.error),
                        [
                            { text: "OK" }
                        ]);
                    }
                }else
                {
                    Alert.alert(
                        "Success",
                        "Data has been successfully saved",
                        [
                            { text: "OK" }
                        ]);
                }
        
            }).catch((error) => { 
                console.error(error);

                setProcessing(false);
      
                Alert.alert(
                "Error",
                JSON.stringify(error),
                [
                    { text: "OK" }
                ]);
            });
    }

  return (
    <ScrollView style={{paddingTop:50}}>
        <View style={styles.container}>
            <Text style={{fontSize: 17, paddingBottom:20}}>
                <Text style={{fontWeight:'bold'}}> Timezone : <Text style={{color:'#f9ad46'}}> {timezone?timezone.toUpperCase():""} </Text> </Text>
            </Text>
        <View style={{padding:20}}>

            <Text style={{fontWeight:'bold', color:'grey', paddingBottom:20}}> Period start date : </Text>
            <TouchableHighlight onPress={showStartDatepicker}>
                <View style={styles.button}>
                <Text>{moment(start_date).format('DD/MM/YYYY HH:mm')}</Text>
                </View>
            </TouchableHighlight>

            {start_show && (
                <DateTimePicker
                    value={start_date}
                    mode={start_mode}
                    is24Hour={true}
                    display="spinner"
                    onChange={onChangeStart}
                />
            )}
        </View>

        <View style={{padding:20}}>

            <Text style={{fontWeight:'bold', color:'grey', paddingBottom:20}}> Period end date : </Text>
            <TouchableHighlight onPress={showEndDatepicker}>
                <View style={styles.button}>
                <Text>{moment(end_date).format('DD/MM/YYYY HH:mm')}</Text>
                </View>
            </TouchableHighlight>

            {end_show && (
                <DateTimePicker
                    value={end_date}
                    mode={end_mode}
                    is24Hour={true}
                    display="spinner"
                    onChange={onChangeEnd}
                />
            )}
        </View>


        <View style={{padding:20}}>

            <Text style={{fontWeight:'bold', color:'grey', paddingBottom:20}}> Period length (min) : </Text>
            <TextInput
                    style={{height: 40}}
                    placeholder="write here..."
                    onChangeText={text => setPeriodLength(text)}
                    value={""+period_length}
                    autoCapitalize = 'none'
                    keyboardType="number-pad"
            />
            
        </View>

        <View style={{padding:20}}>

            <Text style={{fontWeight:'bold', color:'grey', paddingBottom:20}}> Period delay (min) : </Text>
            <TextInput
                    style={{height: 40}}
                    placeholder="write here..."
                    onChangeText={text => setPeriodSleep(text)}
                    value={""+period_sleep}
                    autoCapitalize = 'none'
                    keyboardType="number-pad"
            />
            
        </View>



        {processing && <ActivityIndicator size="large" color='red'/> }
        <Button
            title="Validate"
            onPress={({status, error}) => {addPeriod(start_date, end_date, period_length, period_sleep)}}
        />

        </View>

        <Modal
            animationType="slide"
            transparent={false}
            visible={modalVisible}
            onRequestClose={() => {setModalVisible(!modalVisible);}}
        >

        <View style={{padding:20, paddingBottom:50}}>
            <Text style={{fontWeight:'bold', color:'grey', paddingBottom:20}}> Following events has not been inserted because they overlapped existing events </Text>
            <FlatList
                data={invalid_events}
                renderItem={Item}
                keyExtractor={item => item.id}
                ItemSeparatorComponent={ItemSeparator}
            />
        </View>

        </Modal>

    </ScrollView>
  );
};

const styles = StyleSheet.create({
    container: {
      flex: 1,
      justifyContent: "center",
      paddingHorizontal: 10
    },
    button: {
      alignItems: "center",
      backgroundColor: "#9ce1ce",
      padding: 10
    },
    countContainer: {
      alignItems: "center",
      padding: 10
    },
    countText: {
      color: "#FF00FF"
    }
  });
  


export default App;
