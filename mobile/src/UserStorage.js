import AsyncStorage from '@react-native-community/async-storage';
import * as SecureStore from 'expo-secure-store';

const storeKey = "user_parms";
const secureStoreKey = "user_pwd";
const storeTokenKey = "server_token";

export async function getToken()
{
    try 
    {
        return await AsyncStorage.getItem(storeTokenKey);

    } catch (error) 
    {
        return null;
    }
}

export async function saveToken(value)
{
    try 
    {
        if(value==null)
            await AsyncStorage.removeItem(storeTokenKey);
        else
            await AsyncStorage.setItem(storeTokenKey, value);

        return {
            status: true,
        };


    } catch (error) 
    {
        console.error(error);
        return {
            status: false,
            error: error,
        };
    }
}

function format(field_name, value)
{
    switch(field_name)
    {
        case "url":
            {
                if(value!=null)
                {
                    if(value.substr(value.length - 1)=="/")
                    {
                        value = value.slice(0, -1);
                    }
                }
            }
            break;
        
        default:
            break;
    }

    return value;
}


export async function retrieve()
{
    try 
    {
        var data = JSON.parse(await AsyncStorage.getItem(storeKey));
        var secure_data = JSON.parse(await SecureStore.getItemAsync(secureStoreKey));
        return {
            server: {
                url: format("url", data.server.url),
                id: format("id", data.server.id),
                pwd : format("pwd", secure_data.server.pwd),
            },
            calendar: {
                use: format("calendar.use", data.calendar.use),
                value: format("calendar.value", data.calendar.value),
            },
            formats: {
                before_contact_name: data.formats.before_contact_name,
                before_event_name: data.formats.before_event_name,
            }, 
            language: data.language,
        };


    } catch (error) 
    {
        return {
            server : {
                url : "",
                id: "",
                pwd: ""
            },
            calendar: {
                use: true,
                value: null,
            },
            formats: {
                before_contact_name: "V",
                before_event_name: "V",
            },
            language: null,
        };
    }
}

export async function save(input)
{
    try 
    {
        var data = {
            server: {
                url: input.server.url,
                id: input.server.id
            },
            calendar: {
                use: input.calendar.use,
                value: input.calendar.value,
            },
            formats: {
                before_contact_name: input.formats.before_contact_name,
                before_event_name: input.formats.before_event_name,
            },
            language: input.language,
        };

        var secure_data = {
            server: {
                pwd : input.server.pwd,
            }
        };

        await AsyncStorage.setItem(storeKey, JSON.stringify(data));
        await SecureStore.setItemAsync(secureStoreKey, JSON.stringify(secure_data));

        return {
            status: true
        };

    }catch (error) 
    {
        return {
            status: false,
            error: error,
        };
    }
}
