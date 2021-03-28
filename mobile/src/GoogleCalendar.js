import React from 'react';
import ApiCalendar from 'react-google-calendar-api';

if (ApiCalendar.sign)
    ApiCalendar.listUpcomingEvents(10)
      .then(({result}: any) => {
        console.log(result.items);
      });

export default class App extends React.Component {
    constructor(props) {
        super(props);
        this.handleItemClick = this.handleItemClick.bind(this);
    }

    componentDidMount() {
        // Lifecycle method when your component is mounted
    }

    handleItemClick(event: SyntheticEvent<any>, name: string): void {
        if (name === 'sign-in') {
        ApiCalendar.handleAuthClick();
        } else if (name === 'sign-out') {
        ApiCalendar.handleSignoutClick();
        }
    }

test() {
    ApiCalendar.createEventFromNow(eventFromNow)
    .then((result: object) => {
    console.log(result);
        })
    .catch((error: any) => {
        console.log(error);
        });
}

render() {
    return (
    <View style={styles.container}>
        <Text>Open up App.js to start working on your app!</Text>
        <StatusBar style="auto" />
        <button
                onClick={(e) => this.handleItemClick(e, 'sign-in')}
            >
                sign-in
            </button>
            <button
                onClick={(e) => this.handleItemClick(e, 'sign-out')}
            >
                sign-out
            </button>

            <button
                onClick={(e) => this.test()}
            >
                test
            </button>
    </View>
    );
}
}

const styles = StyleSheet.create({
container: {
    flex: 1,
    backgroundColor: '#fff',
    alignItems: 'center',
    justifyContent: 'center',
},
});
      