import React, { useEffect, useState }  from 'react';
import { Text, TextInput, View, TouchableHighlight, StyleSheet, FlatList, TouchableOpacity, 
    SafeAreaView, Button, Alert, CheckBox, Modal, ActivityIndicator, Switch } from 'react-native';
import {Picker} from '@react-native-community/picker';
import IconFeather from 'react-native-vector-icons/Feather';
import { ScrollView } from 'react-native-gesture-handler';
import * as UserStorage from '../src/UserStorage';
import ServerRoutes from '../src/ServerRoutes';
import axios from '../src/AxiosInstance';
import { Rating } from 'react-native-elements';
import { Searchbar } from 'react-native-paper';

function Separator()
{
    return (
        <View
        style={{
            borderBottomColor: 'lightgrey',
            borderBottomWidth: 1,
            padding:15,
        }}
        />
    );
}



const App = ({}) => {
    const [user_data, setUserData] = useState(null);
    const [event_ref, setEventRef] = useState('');
    const [event, setEvent] = useState(null);
    const [late, setLate] = useState(0);
    const [interested, setInterested] = useState(0);
    const [event_state, setEventState] = useState(null);
    const [rank, setRank] = useState(3);
    const [events_info, setEventsInfo] = useState(null);
    const [processing, setProcessing] = useState(false);
    const [user_info, setUserInfo] = useState("");
    const [no_data, setNoData] = useState(true);
    const [timezone, setTimezone] = useState("");
    const [select_state_options, setSelectStateOptions] = useState([]);
    const [editable_states, setEditableStates] = useState([]);
    const [reason, setReason] = useState("");

    function search(reference)
    {
        if(processing)
            return ;

        setNoData(false);
        setEvent(null);
        
        setProcessing(true);

        axios.post(ServerRoutes.api + "/search/event", null, {
            params: {
                reference: reference,
            }
            }).then((response) => {
                const data = response.data;

                setProcessing(false);

                if("error" in data)
                {
                    Alert.alert(
                    "Error",
                    JSON.stringify(data.error),
                    [
                        { text: "OK" }
                    ]);
                    return ;
                }
                

                const _data = data.data;

                if(_data.length==0 || (("event" in _data) && (_data.event==null)))
                {
                    setNoData(true);
                    return ;
                }else
                {
                    setNoData(false);
                }

                const event = _data.event;
                const user = event.user;
      
                setTimezone(_data.timezone);
                setEvent(event);
                setRank((user.rank<0?3:user.rank));
                setInterested((user.interest==0)?false:true);
                setEventState(event.state);
                setUserInfo(user.info);
                setLate((event.late?event.late:0));
                setEventRef(event.reference);
                setReason(event.reason_state);
        
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


    function updateEvent(reference, state, reason, late, interested, rank, info)
    {
        if(processing)
            return ;
        
        setProcessing(true);

        axios.put(ServerRoutes.api + "/action/updateEvent", null, {
            params: {
                reference: reference,
                state: state,
                reason: reason,
                late: late,
                interest: (interested?1:0),
                rank: rank,
                info: info,
            }
            }).then((response) => {
                const data = response.data;

                setProcessing(false);

                if("error" in data)
                {
                    Alert.alert(
                    "Error",
                    JSON.stringify(data.error),
                    [
                        { text: "OK" }
                    ]);
                    return ;
                }

                Alert.alert(
                    "Validation",
                    "Data has been successfully saved",
                    [
                        { text: "OK" }
                    ]);
                
                search(event_ref)

            }).catch((err) => {
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

    function getStateData(events_info, state)
    {
        const state_name = events_info.reverse_states[state];
        const trad = events_info.traductions.states.values[state_name];

        return {
            label: trad.title, 
            color: trad.color,
            value: state,
        };
    }

    function EventStateComponent()
    {
        if(event)
        {
            if(event.state in editable_states)
            {
                return (
                    <Picker
                        selectedValue={event_state}
                        style={{ height: 50, width: 150 }}
                        onValueChange={(itemValue, itemIndex) => setEventState(itemValue)}
                    >
    
                        {
                            events_info && (
                                select_state_options.map((item) => {
                                    return (
                                        <Picker.Item label={item.label} color={item.color} value={item.value} key={item.value}/>
                                    );
                                })
                            )
                        }
                    </Picker>
                );
            }else
            {
                const item = getStateData(events_info, event.state);
                return <Text style={{color:item.color, paddingBottom:10}}> {item.label} </Text>
            }
        }else
        {
            return <Text style={{paddingBottom:10}}> ... </Text>
        }

    }

    useEffect(() => {
        UserStorage.retrieve().then((user_data) => {
            setUserData(user_data);

            axios.get(ServerRoutes.api + "/info/events", {
                params: {
                    language: user_data.language,
                }
                }).then((response) => {
                    const data = response.data;
                    const event_data = data.data;
                    
          
                    setEventsInfo(data.data);

                    //const not_editable_state_names = ["not_allocated", "waiting_validation", "waiting_cancellation"];
                    const editable_state_names = event_data.updatable_states;

                    var editables_states = {};
                    for(var i in editable_state_names)
                    {
                        const state_name = editable_state_names[i];
                        const state = event_data.states[state_name];

                        editables_states[state] = true;
                    }
                    setEditableStates(editables_states);

                    var options = [];
                    for(var i in event_data.states)
                    {
                        const state = event_data.states[i];
                        const state_name = event_data.reverse_states[state];
                        const trad = event_data.traductions.states.values[state_name];

                        if(!event_data.choosable_states.includes(state_name) && !editable_state_names.includes(state_name))
                        {
                            continue;
                        }

                        options.push({
                            label: trad.title, 
                            color: trad.color,
                            value: state,
                        });
                    }

                    setSelectStateOptions(options);
            
                }).catch((error) => { 
                    console.error(error);
          
                    Alert.alert(
                    "Error",
                    JSON.stringify(error),
                    [
                        { text: "OK" }
                    ]);
                });
        });

    }, []);

    return (
      <SafeAreaView style={{padding: 20, paddingTop:50}}>
          <ScrollView>

        <View>
                <Searchbar
                    placeholder="Search event by reference"
                    onChangeText={(text) => setEventRef(text)}
                    value={event_ref}
                    onIconPress={() => { search(event_ref) }}
                    autoCapitalize="none"
                />
        </View>

        {processing && <ActivityIndicator size="large" color='red' /> }


        {no_data && (
                <View>
                    <Separator/>
                    <Text style={{color:'red'}}> No data </Text>
                </View>
            )
        }

        {event && (
            <View>
                <View style={{paddingTop:20}}>
                    <Text style={{color:'grey'}}> Event reference </Text>
                    <Text style={{fontWeight:'bold'}}> {event.reference} </Text>

                    <Text style={{color:'grey'}}> Event id </Text>
                    <Text style={{fontWeight:'bold'}}> {event.id} </Text>

                    <Separator/>

                    <Text style={{color:'grey'}}> Start date </Text>
                    <Text style={{fontWeight:'bold'}}> {event.start_date} </Text>

                    <Text style={{color:'grey'}}> End date </Text>
                    <Text style={{fontWeight:'bold'}}> {event.end_date} </Text>

                    <Text style={{color:'grey'}}> Time zone </Text>
                    <Text style={{fontWeight:'bold'}}> {timezone} </Text>
                </View>

                <Separator/>
                <View style={{paddingBottom:10}}/>

                <View>
                    <Text style={{color:'grey'}}> User id </Text>
                    <Text style={{fontWeight:'bold'}}> {event.user.id} </Text>

                    <Text style={{color:'grey'}}> User phone </Text>
                    <Text style={{fontWeight:'bold'}}> {event.user.phone} </Text>
                    
                </View>
            </View>
        )} 

        <Separator/>

        {!no_data && (
            <View>
                <Text style={{color:'grey'}}> Event status </Text>
                <EventStateComponent/>

                <Text style={{color:'grey'}}> Reason </Text>
                <TextInput
                    placeholder="write here ..."
                    onChangeText={text => setReason(text)}
                    defaultValue={reason}
                    autoCapitalize = 'none'
                    multiline = {true}
                    numberOfLines = {5}
                />

            <Separator/>

                <Text style={{color:'grey'}}> Personal feelings </Text>
                <Rating
                    ratingColor='#3498db'
                    ratingBackgroundColor='#c8c7c8'
                    ratingCount={5}
                    imageSize={30}
                    startingValue={rank}
                    initialRating={rank}
                    onFinishRating={(value) => setRank(value)}
                    style={{ paddingVertical: 10 }}
                />

            <Separator/>

                <View style={{flexDirection:'row'}}>
                    <Text style={{color:'grey'}}> Interested ? </Text>
                    <Switch
                        trackColor={{ false: "#767577", true: "#81b0ff" }}
                        thumbColor={interested ? "#f5dd4b" : "#f4f3f4"}
                        ios_backgroundColor="#3e3e3e"
                        onValueChange={(value) => {setInterested(value)}}
                        value={interested}
                    />
                </View>

            <Separator/>

                <Text style={{color:'grey'}}> Delay </Text>
                <TextInput
                placeholder="delay in minutes"
                onChangeText={text => setLate(text)}
                defaultValue={""+late}
                autoCapitalize = 'none'
                keyboardType="number-pad"
                />

            <Separator/>

                <Text style={{color:'grey'}}> Info </Text>
                <TextInput
                    placeholder="write here ..."
                    onChangeText={text => setUserInfo(text)}
                    defaultValue={user_info}
                    autoCapitalize = 'none'
                    multiline = {true}
                    numberOfLines = {5}
                />

            <View style={{paddingTop:20}}/>
            <Button
            title="Validate"
            onPress={({status, error}) => {
                updateEvent(event_ref, event_state, reason, late, interested, rank, user_info);
            }}
            />
        </View>
        )}
        </ScrollView>
      </SafeAreaView>
    );
  }

export default App;