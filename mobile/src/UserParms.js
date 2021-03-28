import React, { useEffect, useState }  from 'react';
import { Text, TextInput, View, Picker, TouchableHighlight, StyleSheet, FlatList, TouchableOpacity, SafeAreaView, Button, Alert, CheckBox, Modal, ActivityIndicator } from 'react-native';
import * as Calendar from 'expo-calendar';
import FontistoIcon from 'react-native-vector-icons/Fontisto';
import { ScrollView } from 'react-native-gesture-handler';
import * as UserStorage from '../src/UserStorage';
import _axios from 'axios';
import ServerRoutes from '../src/ServerRoutes';
import * as Updates from 'expo-updates';

/*
    data: list of oject
    keyExtractor: id
    selectionChange: function handle id
    renderItem: function
*/
class ListViewSelect extends React.Component {

  constructor(props) {
    super(props);

    this.state = {
        selectedItem: props.selectedItem,
        modal_visible: false,
    };
  }

  static getDerivedStateFromProps(props, current_state) {
    if (current_state.selectedItem !== props.selectedItem) {
      return {
        selectedItem: props.selectedItem,
      }
    }
    return null
  }


  _getItemId = (item) => {
        if(!item)
            return null;
        
        return item[this.props.keyExtractor];
  }

  _isSelected = (item) => {
        return (this._getItemId(this.state.selectedItem) == this._getItemId(item));
  }

  _clickItem = (item) => {
      this.setState({
        selectedItem: item,
        modal_visible: false,
      });

      if(this.props.onChange)
        this.props.onChange(item);
  }
  
  _renderSeparator = () => {
    return (<View
    style={{
        margin:10,
        borderBottomColor: 'lightgrey',
        borderBottomWidth: 1,
    }}
    />);
  }

  _renderItem = ({item}) => {

    return (
        <TouchableHighlight
            activeOpacity={0.6}
            underlayColor="#DDDDDD"
            onPress={() => {this._clickItem(item);}}>
                <SafeAreaView style={{flexDirection:'row', alignItems: "center"}} >
                    <View style={{flex:1}}>
                        {this.props.renderItem({item})}
                    </View>
                    <View style={{felx:1, marginLeft:"auto"}}>
                        <FontistoIcon
                            name={(this._isSelected(item)?"checkbox-active":"checkbox-passive")}
                            size={20}
                        />
                    </View>
                </SafeAreaView> 
        </TouchableHighlight>
    );
  }

  render() {
    return (
        <View style={{}}>
            <TouchableHighlight
                activeOpacity={0.6}
                underlayColor="#DDDDDD"
                onPress={() => {this.setState({modal_visible: true});}}>
                    <View
                    style={{
                        padding:10
                    }}
                >
                    <Text style={{fontWeight:"bold", color:"grey", marginBottom:10}}> Selection : </Text>
                    {this.state.selectedItem?this.props.renderItem({item: this.state.selectedItem}):<Text style={{}}> no selection: </Text>}
                </View>
            </TouchableHighlight>
                

            <View
            style={{
                margin:10,
                borderBottomColor: 'lightgrey',
                borderBottomWidth: 1,
            }}
            />

            <Modal
                animationType="slide"
                transparent={false}
                visible={this.state.modal_visible}
                onRequestClose={() => {this.setState({modal_visible: false})}}
            >

                <FlatList
                    data={this.props.dataSource}
                    renderItem={this._renderItem}
                    ItemSeparatorComponent={this._renderSeparator}
                    keyExtractor={item => this._getItemId(item)}
                />
            </Modal>
            
        </View>
        
      );
  }
};

/*
--------------------------------------------------------------------

--------------------------------------------------------------------
*/


function Item({item}) {
    return (
      <View style={{}}>
        <Text style={{}}> Title: {item.title}</Text>
        <Text style={{}}> Owner : {item.owner}</Text>
        <Text style={{}}> Account : {(item.isLocalAccount?"LOCAL":"REMOTE")}</Text>
      </View>
    );
}


const App = ({navigation}) => {
    const [server_url, setServerUrl] = useState('');
    const [server_id, setServerId] = useState('');
    const [server_pwd, setServerPwd] = useState('');
    const [default_calendar, setDefaultCalendar] = useState(null);
    const [available_calendars, setAvailableCalendars] = useState([]);
    const [use_calendar, setUseCalendar] = useState(true);
    const [before_contact_name, setBeforeContactName] = useState("V");
    const [before_event_name, setBeforeEventName] = useState("V");
    const [language, setLanguage] = useState(null);
    const [server_connexion, setServerConnexion] = useState(null);
    const [processing, setProcessing] = useState(false);

    useEffect(() => {
        (async () => {
          const { status } = await Calendar.requestCalendarPermissionsAsync();
          if (status === 'granted') 
          {
            const calendars_data = await Calendar.getCalendarsAsync(Calendar.EntityTypes.EVENT);

            const calendars  = [];
            for(var key in calendars_data)
            {
                const calendar = calendars_data[key];

                if(calendar.accessLevel!="owner")
                    continue;
                
                calendars.push({
                    id: calendar.id,
                    name: calendar.name,
                    title: calendar.title,
                    isLocalAccount: calendar.source.isLocalAccount,
                    owner: calendar.ownerAccount,
                    distant_name: calendar.source.name,
                });
            }

            setAvailableCalendars(calendars);
          }
          
          const data = UserStorage.retrieve().then((data) => {
            setServerUrl(data.server.url);
            setServerId(data.server.id);
            setServerPwd(data.server.pwd);
            setDefaultCalendar(data.calendar.value);
            setUseCalendar(data.calendar.use);
            setBeforeContactName(data.formats.before_contact_name);
            setBeforeEventName(data.formats.before_event_name);
            setLanguage(data.language);
          });

        })();
      }, []);

    return (
      <SafeAreaView style={{padding: 20, paddingTop:50}}>
          <ScrollView>
            
            <View>
                <Text style={{fontWeight: 'bold', paddingBottom: 10}}> Server Credentials </Text>

                <Text style={{color:'grey'}}> server url </Text>
                <TextInput
                style={{height: 40}}
                placeholder="Url"
                onChangeText={text => setServerUrl(text)}
                value={server_url}
                autoCapitalize = 'none'
                />

                <Text style={{color:'grey'}}> user id </Text>
                <TextInput
                style={{height: 40}}
                placeholder="Phone"
                onChangeText={text => setServerId(text)}
                value={server_id}
                autoCapitalize = 'none'
                />

                <Text style={{color:'grey'}}> user password </Text>
                <TextInput
                style={{height: 40}}
                placeholder="Password"
                onChangeText={text => setServerPwd(text)}
                value={server_pwd}
                secureTextEntry={true}
                autoCapitalize = 'none'
                />

            <View style={{paddingTop:10, flexDirection:'row'}}/>
                <Button
                        title="Connexion test"
                        onPress={({status, error}) => {
                            var url = server_url;
                            if(url && (url.substr(url.length - 1)=="/"))
                            {
                                url = url.slice(0, -1);
                            }

                            _axios.post(url + ServerRoutes.api + "/auth/login", null, {
                                params: {
                                    phone: server_id,
                                    password: server_pwd,
                                }
                                }).then((response) => {
                                    const data = response.data;
                                    
                                    if(data && !("error" in data))
                                        setServerConnexion(true);
                                    else
                                        setServerConnexion(false);
                            
                                }).catch((err) => { 
                                    setServerConnexion(false);
                                });
                        }}
                    />


                <Text style={{marginTop:5, color: "white", textAlign:'center', backgroundColor:(server_connexion==null?"transparent":(server_connexion?"#acf077":"#f47665"))}}>  
                    { (server_connexion==null?"":(server_connexion?"OK":"ERROR")) }
                </Text> 

                    
                    
                <View style={{paddingTop:10}}/>


                <View style={{paddingTop:10}}/>
                <Button
                        title="Reset saved token"
                        onPress={({status, error}) => {
                            UserStorage.saveToken(null).then((response)=> {
                                var content = "";
            
                                if(response.status)
                                    content = "Data has been saved successfully";
                                else
                                    content = "An error occurs while trying to save data : " + JSON.stringify(response.error);
                                 
                                Alert.alert(
                                "Saving status",
                                content,
                                [
                                    { text: "OK" }
                                ]);
                            });
                        }}
                    />
                <View style={{paddingTop:10}}/>
            </View>

            <View styel={{paddingTop:20, paddingBottom:10}}>
                
                <Text style={{fontWeight: 'bold', paddingBottom: 10}}> Formats </Text>

                <Text style={{color:'grey'}}> Before user name in contacts list (ex witht "Visit" : for user id 1,2,3 : "Visit 1", "Visit 2", "Visit 3") </Text>
                <TextInput
                style={{height: 40}}
                placeholder="Url"
                onChangeText={text => setBeforeContactName(text)}
                value={before_contact_name}
                autoCapitalize = 'none'
                />

                <Text style={{color:'grey'}}> Before event name in calendar (ex with "Event" : for user id mlOp,lkde,pozl : "Event mlOp", "Visit lkde", "Visit pozl") </Text>
                <TextInput
                style={{height: 40}}
                placeholder="Phone"
                onChangeText={text => setBeforeEventName(text)}
                value={before_event_name}
                autoCapitalize = 'none'
                />
            </View>

            <View styel={{paddingTop:20, paddingBottom:10}}>
                
                <Text style={{fontWeight: 'bold', paddingBottom: 10}}> Language </Text>

                <Text style={{color:'grey'}}> Language code </Text>
                <TextInput
                style={{height: 40}}
                placeholder="language"
                onChangeText={text => setLanguage(text)}
                value={language}
                autoCapitalize = 'none'
                />
            </View>

            <Text style={{fontWeight: 'bold', paddingBottom: 10}}> Calendar </Text>

            <View style={{paddingTop:20, paddingBottom:10}}>
                <Text style={{}}>With calendar injection (add/remove events to a local/remote calendar set on your phone)</Text>
                <CheckBox
                value={use_calendar}
                onValueChange={setUseCalendar}
                style={{alignSelf: "center"}}
                />
            </View>

            <View style={{paddingTop:20}}>
                <Text style={{fontWeight: 'bold'}}> Default calendar </Text>

                <ListViewSelect dataSource={available_calendars} keyExtractor="id" renderItem={Item} onChange={(item) => { setDefaultCalendar(item); }} selectedItem={default_calendar}/>
            </View>

            <View style={{paddingTop:10}}/>

            {processing && <ActivityIndicator size="large" color='red' /> }

            <Button
            title="Save"
            onPress={({status, error}) => {

                if(processing)
                    return ;
                
                setProcessing(true);

                const data = {
                    server : {
                        url:server_url,
                        id: server_id,
                        pwd: server_pwd,
                    },
                    calendar: {
                        use: use_calendar,
                        value: default_calendar
                    },
                    formats: {
                        before_contact_name: before_contact_name,
                        before_event_name: before_event_name,
                    },
                    language: language,
                };

                UserStorage.save(data).then((response)=> {
                    let content = "";

                    if(response.status)
                    {
                        content = "Data has been saved successfully.\nThe app is going to restart";
                        Alert.alert(
                            "Saving status",
                            content,
                            [
                              {
                                text: 'ok',
                                onPress: () => {
                                    setProcessing(false);

                                    Updates.reloadAsync().then((data) => {//because problems with data updating
                                            
                                    });
                                },
                              },
                            ],
                            { cancelable: false },
                        );
                        
                    }else
                    {   
                        setProcessing(false);
                        content = "An error occurs while trying to save data : " + JSON.stringify(response.error);

                        Alert.alert(
                            "Saving status",
                            content,
                            [
                                { text: "OK" }
                        ]);
                    }
                });
            }}
        />
        </ScrollView>
      </SafeAreaView>
    );
  }

export default App;