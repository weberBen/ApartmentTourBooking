import React, { createRef, useEffect, useState, useRef} from "react";
import { StyleSheet, Text, View, Button, Alert, TextInput, Modal, CheckBox, ActivityIndicator } from "react-native";
import SwipeCards from "react-native-swipe-cards-deck";
import ServerRoutes from '../src/ServerRoutes';
import axios from '../src/AxiosInstance';
import * as SMS from 'expo-sms';
import * as UserStorage from '../src/UserStorage';
import * as Calendar from 'expo-calendar';
import moment from 'moment';

const ACTION_TYPE_COLORS = {
  "book": "#6f89e5",
  "unbook": "#e5866f"
};

function Card({ data, actionParms}) {

  if(!data || !actionParms)
    return <View style={{}}></View>

  const type_name = actionParms.reverse_types[data.type];
  const public_data = JSON.parse(data.public_data);
  const event = public_data.calendar_event;

  return (
    <View style={{}}>
      <Text style={{backgroundColor:ACTION_TYPE_COLORS[type_name], textAlign: 'center', fontSize:30, padding:5, marginBottom:10}}>{type_name.toUpperCase()}</Text>
      <Text> 
        <Text style={{fontWeight:"bold"}}> Time zone : </Text> 
        <Text style={{}}>{event.timezone}</Text>
      </Text>
      <Text> 
        <Text style={{fontWeight:"bold"}}> Début Créneau : </Text> 
        <Text style={{}}>{event.start_date}</Text>
      </Text>
      <Text> 
        <Text style={{fontWeight:"bold"}}> Fin Créneau : </Text> 
        <Text style={{marginRight:"auto"}}>{event.end_date}</Text>
      </Text>
      <Text> 
        <Text style={{fontWeight:"bold"}}> Raison : </Text> 
        <Text style={{marginRight:"auto"}}>{data.reason_state}</Text>
      </Text>
      <Text> 
        <Text style={{fontWeight:"bold"}}> Date de récéption : </Text> 
        <Text style={{marginRight:"auto"}}>{data.updated_at}</Text>
      </Text>
    </View>
  );
}

function StatusCard({ text }) {
  return (
    <View>
      <Text style={styles.cardsText}>{text}</Text>
    </View>
  );
}

const NOTE_LINES = [
  { 
    name: "warning",
    value: "DO NOT BEGINNING THE BEGINING OF THAT NOTE]",
  },
  {
    name: "event_ref",
    value: "Event reference :",
  },
  {
    name: "event_id",
    value: "Event id :",
  },
  {
    name: "phone",
    value: "Phone :",
  },
  {
    name: "end_warning",
    value: "[END DO NOT EDIT]"
  }
];

function buildCalendarNote(event_ref, event_id, phone)
{
  var output = "";
  for(var i in NOTE_LINES)
  {
    const line = NOTE_LINES[i];

    output += line.value + " ";

    switch(line.name)
    {
      case "event_ref":
        {
          output += event_ref;
        }
        break;
      case "event_id":
      {
        output += event_id;
      }
      break;
      case "phone":
      {
        output += phone;
      }
      break;
    }
    output += "\n";
  }

  return output;              
}

function parseCalendarNote(txt)
{
  //bad but ok

  var output = {};
  const lines = txt.split(/\r?\n/);
  var count = 0;
  for(var i in lines)
  {
    if(count>=NOTE_LINES.length)
      break;
    count+=1;
    
    const line = lines[i];
    for(var j in NOTE_LINES)
    {
      const note_line = NOTE_LINES[j];
      if(line.startsWith(note_line.value))
      {
        output[note_line.name] = line.replace(note_line.value + " ", "");
      }
    }

  }

  return output;
}

export default function App() {
  const [cards, setCards] = useState();
  const [action_parms, setActionParms] = useState();
  const [reason, setReason] = useState(null);
  const [modalVisible, setModalVisible] = useState(false);
  const [send_sms, setSendSms] = useState(true);
  const [discardedCard, setDiscardedcard] = useState(null);
  const [processing, setProcessing] = useState(false);
  const [swipe_state, setSwipeSate] = useState(null);
  const [refreshing, setRefreshing] = useState(null);
  const [user_data, setUserData] = useState(null);

  const swiper = useRef();

  function handleSwipe(swipe_state, action)
  {
    setSwipeSate(swipe_state);
    setDiscardedcard(action);
    setReason("");
    setSendSms(true);
    setModalVisible(true);

  }

  function error(err)
  {
    setProcessing(false);
    setModalVisible(false);

    Alert.alert(
    "Error",
    JSON.stringify(err),
    [
        { text: "OK" }
    ]);

    swiper.current._goToPrevCard();
  }

  function updateActionSate(action, state, reason, language)
  {
    if(processing)
      return ;
    
    setProcessing(true);

    const id_action = action.id;

    axios.put(ServerRoutes.api + "/action/updateActionState", null, {
      params: {
        state: state,
        id_action: id_action,
        reason: reason,
        language: language,
      }
      }).then((response) => {
          const data = response.data;

          setProcessing(false);

          if("error" in data)
          {
            console.error(data);
            error(data);
            return ;
          }

          if(user_data.calendar.use)
          {
          
            if(user_data.calendar.value==null)
            {
              Alert.alert(
                "Error",
                "Calendar option activated but no calendar id set",
                [
                    { text: "OK" }
                ]);
            }

            const public_data = JSON.parse(action.public_data);
            const event = public_data.calendar_event;

            const start_date = moment(event.start_date, "YYYY-MM-DD HH:mm").toDate();
            const end_date = moment(event.end_date, "YYYY-MM-DD HH:mm").toDate();


            switch(action_parms.reverse_types[action.type])
            {
              case "book":
                {
                  if(state=="validate")
                  {
                    const title = user_data.formats.before_event_name + " " + event.reference;
                    const details = {
                      title: title,
                      startDate: start_date,
                      endDate: end_date,
                      allDay: false,
                      notes: buildCalendarNote(event.reference, event.id, action.user.phone),
                    };
                    
                    
                      Calendar.createEventAsync(user_data.calendar.value.id, details).then((event_id) => {

                      }).catch((err) => {
                        console.error(err);

                        Alert.alert(
                          "Error (calendar add action for event ref : " + event.reference + ")",
                          JSON.stringify(err),
                          [
                              { text: "OK" }
                          ]);
                      });
                  }
                }
                break;

                case "unbook":
                {
                  if(state=="validate")
                  {
                    Calendar.getEventsAsync([user_data.calendar.value.id], start_date, end_date).then((events) => {

                      var parsed_events = [];
                      for(var i in events)
                      {
                        const _event = events[i];
                        const note = parseCalendarNote(_event.notes);

                        if(note.event_id==event.id)
                        {
                          Calendar.deleteEventAsync(_event.id).then(() => {

                          }).catch((err) => {
                            console.error(err);
    
                            Alert.alert(
                              "Error (calendar delete action for event ref : " + event.reference + ")",
                              JSON.stringify(err),
                              [
                                  { text: "OK" }
                              ]);
                          });
                        }
                      }

                    }).catch((err) => {
                      console.error(err);

                      Alert.alert(
                        "Error",
                        JSON.stringify(err),
                        [
                            { text: "OK" }
                        ]);
                    });
                  }
                }
                break;
            }


            const msg_to_send = data.msg_to_send;
            if(send_sms)
            {
              SMS.sendSMSAsync(
                [action.user.phone],
                msg_to_send,
                {}
              );
            }

            
          }
          

          setModalVisible(false);
          
  
      }).catch((err) => { 
          console.error(err);
          error(err);

      });
    
  }


  function refresh() 
  {
    setRefreshing(true);

    axios.get(ServerRoutes.api + "/action/waitingActions", null, {
      params: {
      }
      }).then((response) => {
          const data = response.data;

          setActionParms(data.data);
          setCards(data.data.values);

          setRefreshing(false);
  
      }).catch((error) => { 
          console.error(error);

          setRefreshing(false);

          Alert.alert(
          "Error",
          JSON.stringify(error),
          [
              { text: "OK" }
          ]);
      });

      return true;
  }

  function handleYup(card) 
  {
      handleSwipe("validate", card);

      return true;
  }

  function handleNope(card) 
  {
    handleSwipe("cancel", card);

    return true;
  }

  function handleMaybe(card) 
  {
    return true;
  }

  useEffect(() => {
    UserStorage.retrieve().then((user_data) => {
      setUserData(user_data);
    });

    refresh();
  }, []);

  return (
      <View style={styles.container}>
      <Modal
        animationType="slide"
        transparent={false}
        visible={modalVisible}
        onRequestClose={() => {}}
      >
        {processing && <ActivityIndicator size="large" color='red' /> }

        <View style={{pading:20, flex: 1, alignItems: 'center', justifyContent: 'center', }}>
        
        <View style={{}}>
          <Text style={{fontWeight:"bold", paddingBottom:5}}> Reason </Text> 
          <TextInput
              style={{backgroundColor: "#ECE9E9", width:"100%"}}
              placeholder="Write here ..."
              autoFocus={true}
              onChangeText={text => setReason(text)}
              defaultValue={reason}
              multiline={true}
              numberOfLines={10}
            />
        </View>

        <View style={{width:"90%", borderBottomColor: 'lightgrey', borderBottomWidth: 2, paddingBottom:20}}/>

          <View style={{flexDirection: "row", paddingTop:10}}>
            <Text style={{}}>Send sms ?</Text>
            <CheckBox
              value={send_sms}
              onValueChange={setSendSms}
              style={{alignSelf: "center"}}
            />
          </View>

          <View style={{width:"90%", borderBottomColor: 'lightgrey', borderBottomWidth: 2, paddingBottom:20}}/>

          <View style={{position:"absolute", top:50, flexDirection:"row"}}>
            <Button
              title="Cancel"
              color="#FE7575"
              onPress={({status, error}) => {
                swiper.current._goToPrevCard();
                setModalVisible(false);
              }}
            />
            <View style={{marginLeft:50}}/>
            {refreshing && <ActivityIndicator size="large" color='red' /> }
             <Button
              title="Confirm"
              onPress={({status, error}) =>  {
                updateActionSate(discardedCard, swipe_state, reason, user_data.language);
              }}
            />
          </View>
        </View>

      </Modal>
        {cards ? (
          <SwipeCards
            ref={swiper}
            cards={cards}
            renderCard={(cardData) => <Card data={cardData} actionParms={action_parms} />}
            keyExtractor={(cardData) => String(cardData.text)}
            renderNoMoreCards={() => <StatusCard text="No more actions..." />}
            handleYup={handleYup}
            handleNope={handleNope}
            handleMaybe={handleMaybe}
            hasMaybeAction={true}

            // If you want a stack of cards instead of one-per-one view, activate stack mode
            // stack={true}
            // stackDepth={3}
          />
        ) : (
          <StatusCard text="Loading..." />
        )}

      <Button
            title="Refresh"
            onPress={({status, error}) => { refresh(); }}
        />
        <View style={{paddingBottom:15}}/>
      </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: "#fff",
    alignItems: "center",
    justifyContent: "center",
  },
  card: {
    justifyContent: "center",
    alignItems: "center",
    width: 300,
    height: 300,
  },
  cardsText: {
    fontSize: 22,
  },
});