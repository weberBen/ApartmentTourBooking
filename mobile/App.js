import * as React from 'react';
import { Button, View } from 'react-native';
import { createDrawerNavigator } from '@react-navigation/drawer';
import { NavigationContainer } from '@react-navigation/native';
import Icon from 'react-native-vector-icons/Octicons';
import UserParms from './src/UserParms';
import axios from './src/AxiosInstance';
import ServerRoutes from './src/ServerRoutes';
import { createIconSetFromFontello } from 'react-native-vector-icons';
import EventApproval from './src/EventApproval';
import RegisterUser from './src/RegisterUser';
import EventUpdate from './src/EventUpdate';
import BookableEvents from './src/BookableEvents';

const Drawer = createDrawerNavigator();


export default function App() {
  return (
    <NavigationContainer>
      <Drawer.Navigator initialRouteName="Éevent">
        <Drawer.Screen name="events" component={EventApproval} options={{
          title: 'Events approval'
        }}/>
        <Drawer.Screen name="event_update" component={EventUpdate} 
         options={{
          title: 'Event update',
         }}/>
        <Drawer.Screen name="bookable_event" component={BookableEvents} 
         options={{
          title: 'Add new event',
         }}/>
         <Drawer.Screen name="add_user" component={RegisterUser} 
         options={{
          title: 'Add user',
         }}/>
        <Drawer.Screen name="parms" component={UserParms}
        options={{
          title: 'Settings',
         }}/>
      </Drawer.Navigator>
    </NavigationContainer>
  );
}