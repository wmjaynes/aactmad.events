<template>


    <div>


        <div class="has-text-centered loading" v-if="loading">
            <a class="button event-button is-info is-loading"> Loading </a>
        </div>

        <div v-else-if="!this.no_controls">
            <div class="field is-grouped is-grouped-centered">

                <div class="field has-addons">
                    <div class="control has-icons-left">
                        <input class="input is-narrow" type="text" placeholder="Quick Search"
                               v-model="quickSearchQuery"> <span class="icon is-small is-left">
                      <i class="fas fa-search"></i>
                    </span>
                    </div>

                    <p class="control">
                        <a class="button event-button is-info" @click.prevent="clearQuickSearch()"> Clear </a>
                    </p>
                </div>
                <div class="field">
                    <p class="control">
                        <a class="button event-button is-info" @click.prevent="toggleAll()"> {{showHideLabel()}}<br>Details
                        </a>
                    </p>
                </div>
            </div>

            <div class="level has-text-centered has-background-info">
                <div class="level-left">
                    <a class="button event-button is-info is-narrow-button" @click="decrementDisplayPeriod()"> <span
                            class="icon"><i class="far fa-hand-point-left"></i></span> <span>Previous</span> </a>
                </div>
                <div class="level-item">
                    <strong> {{this.startOfDisplayPeriod.format("MMM YYYY")}} -
                        {{this.endOfDisplayPeriod.format("MMM YYYY")}} </strong>
                </div>
                <div class="level-right">
                    <a class="button event-button is-info is-narrow-button" @click="incrementDisplayPeriod()"> <span>Next</span>
                        <span class="icon"><i class="far fa-hand-point-right"></i></span> </a>
                </div>
            </div>
        </div>

        <div class="notification is-info has-text-centered" v-if="norecords">
            No records found.
        </div>

        <div class="columns is-multiline">
            <div v-for="eventsInMonth in eventsByMonth" :key="eventsInMonth.monthYear"
                 class="box current-month column" :class="{'is-half': !one_column, 'is-12': one_column}">

                <div class="month-year-tag has-background-info">{{ eventsInMonth.monthYear }}</div>

                <div v-for="eventsInDay in eventsInMonth.eventsByDay" :key="eventsInDay.monthDay"
                    class="box current-day">

                    <div class="month-day-tag">{{ eventsInDay.weekDayMonthDay}}</div>

                    <div v-for="event in eventsInDay.events" :key="event.uniqueId">
                        <event-container :event="event"></event-container>
                    </div>

                </div>

            </div>
        </div>
    </div>


</template>

<script>

    import queryString from 'query-string'

    export default {
        name: "events-listing",
        props: {
            endpoint: {
                type: String,
                default: 'https://events.aactmad.org/eventServiceJson.php'
            },
            orgid: Number,
            nocontrols: {
                type: Boolean,
                default: false,
            },
            onecolumn: {
                type: Boolean,
                default: false,
            },
        },
        data() {
            return {
                events: [],
                quickSearchQuery: "",
                showall: false,
                loading: true,
                lastEventDate: null,
                displayPeriodInMonths: 5,
                startOfDisplayPeriod: moment().startOf('day'),
                endOfDisplayPeriod: moment().add(5, "month").endOf("month"),
                norecords: false,
                no_controls: {
                    type: Boolean,
                    default: false
                },
                one_column: false,
            }
        },

        methods: {
            getRecords() {
                this.processPropsInQueryString();
                let url = this.endpoint + "?" + this.getQueryParameters();
                axios.get(url)
                    .then(response => {

                        this.enhanceEvents(response.data);
                        this.events = response.data;
                        this.loading = false;
                        this.norecords = this.events.length == 0;
                    })
                    .catch(error => {
                        console.log(error)
                    });
            },
            processPropsInQueryString() {
                let parsed = queryString.parse(location.search);
                if (typeof parsed.onecolumn !== 'undefined')
                    this.one_column = parsed.onecolumn == true ? true : false;
                if (typeof parsed.nocontrols !== 'undefined')
                    this.no_controls = parsed.nocontrols == true ? true : false;
                if (typeof parsed.orgId !== 'undefined' && typeof parsed.orgId == 'number')
                    this.orgid = parsed.orgId;
            },
            getQueryParameters() {
                let parsed = queryString.parse(location.search);
                let newEnd = !!parsed.endCalendar;
                let newStart = !!parsed.startCalendar;
                if (newStart) this.startOfDisplayPeriod = moment(parsed.startCalendar, "MM/DD/YYYY");
                if (newEnd)
                    this.endOfDisplayPeriod = moment(parsed.endCalendar, "MM/DD/YYYY");
                else {
                    this.endOfDisplayPeriod = this.startOfDisplayPeriod.clone().startOf('day').add(5, "month").endOf("month");
                }
                let orgid = parsed.orgId ? parsed.orgId : this.orgid;
                let queryParms =
                    {
                        startCalendar: this.startOfDisplayPeriod.format("MM/DD/YYYY"),
                        endCalendar: this.endOfDisplayPeriod.format("MM/DD/YYYY"),
                        orgId: orgid,
                    };

                return queryString.stringify(queryParms)
            },
            getLastEventDate() {
                axios.get(this.endpoint)
                    .then(response => {
                        this.lastEventDate = response.data.pop().EventEnd;
                    })
                    .catch(error => {
                        console.log(error)
                    });
            },
            incrementDisplayPeriod() {
                // location.search = "";
                this.startOfDisplayPeriod.add(6, 'month').startOf('month');
                this.endOfDisplayPeriod = this.startOfDisplayPeriod.clone().startOf('day').add(5, "month").endOf("month");
                this.getRecords();
            },
            decrementDisplayPeriod() {
                // location.search = "";
                this.startOfDisplayPeriod.subtract(6, 'month').startOf('month');
                this.endOfDisplayPeriod = this.startOfDisplayPeriod.clone().startOf('day').add(5, "month").endOf("month");
                this.getRecords();
            },
            clearQuickSearch() {
                this.quickSearchQuery = '';
            },
            toggleAll() {
                this.showall = !this.showall;
                let events = this.events.map(event => {
                    event.displayBody = this.showall;
                    return event;
                });
            },
            showHideLabel() {
                return this.showall ? "Hide" : "Show";
            },
            enhanceEvents(events) {
                if (!events) return;

                let locale = "en-us";
                events.forEach(event => {
                    event.eventStartDate = moment(event.EventStart);
                    event.eventEndDate = moment(event.EventEnd);

                    if (event.eventEndDate.isSame(event.eventStartDate, 'day')) {
                        event.eventEndDateFormated = event.eventEndDate.format('h:mm a');
                        event.eventStartDateFormated = event.eventStartDate.format('h:mm a');
                    }
                    else {
                        event.eventEndDateFormated = event.eventEndDate.format('ddd h:mm a');
                        event.eventStartDateFormated = event.eventStartDate.format('ddd h:mm a');
                    }

                    event.sortDate = moment(event.eventStartDate);
                    event.uniqueId = event.EventID + "." + event.ReID;
                    // event.EventPrettyDate = eventStartDate.toString("ddd MMM dd, yyyy");
                    event.EventDateMonthYear = event.eventStartDate.format("MMMM YYYY");
                    event.EventDateWeekDayMonthDay = event.eventStartDate.format("ddd MMM DD");
                    event.EventDateMonth = event.eventStartDate.format("MMM");

                    // event.EventDateMonthDay = event.eventStartDate.format("MMM DD");
                    event.EventDateMonthDay = event.sortDate.format("MMM DD");

                    event.EventDateMonthFull = event.eventStartDate.format("MMMM");
                    event.EventDateYear = event.eventStartDate.format("YYYY");

                    event.displayBody = this.showall;
                });
                this.handleMultidayEvents(events);
            },

            handleMultidayEvents(events) {

                let multidayevents = events.filter(event => {
                    if (event.eventStartDate.isSame(event.eventEndDate, 'day'))
                        return false;
                    let endOfStartDay = moment(event.eventStartDate).endOf('day');
                    if (endOfStartDay.add(8, 'hours').isAfter(event.eventEndDate))
                        return false;
                    return true;
                });
                console.log('multidayevents: '+JSON.stringify(multidayevents));
                let additionalDays = [];
                multidayevents.forEach(event => {
                    let numOfDays = event.eventEndDate.endOf('day').diff(event.eventStartDate.startOf('day'), 'days');
                    console.log("numOfDays: " + numOfDays);
                    for (let i = 1; i <= numOfDays; i++) {
                        let newDay = Object.assign({}, event);
                        newDay.sortDate = moment(newDay.sortDate).add(i, 'day').startOf('day');
                        newDay.EventDateMonthDay = newDay.sortDate.format("MMM DD");
                        newDay.EventDateWeekDayMonthDay = newDay.sortDate.format("ddd MMM DD");
                        newDay.uniqueId += "."+i;
                        additionalDays.push(newDay);
                        let index = events.findIndex(event => newDay.sortDate.isBefore(event.eventStartDate) );
                        console.log("index: "+index+" : "+JSON.stringify(newDay.sortDate));
                        events.splice(index, 0, newDay);
                        console.log(JSON.stringify(events));
                    }
                });
                console.log('additionalDays: '+JSON.stringify(additionalDays));
            },

            insertNewEvent(events, newEvent) {
              let index = events.findIndex(event => newEvent.eventStartDate.isBefore(event.eventStartDate) );
              console.log("index: "+index);
            },

            generateEventsByDay(events) {
                let eventsByDay= [];
                let eventsInADay= {
                    monthDay: "",
                    monthYear: "",
                    events: [],
                };
                eventsInADay = null;
                let currentMonthDay = "";
                events.forEach(event => {

                    if (currentMonthDay !== event.EventDateMonthDay) {
                        currentMonthDay = event.EventDateMonthDay;
                        if (eventsInADay) eventsByDay.push(eventsInADay);
                        eventsInADay = {
                            monthDay: event.EventDateMonthDay,
                            monthYear: event.EventDateMonthYear,
                            weekDayMonthDay: event.EventDateWeekDayMonthDay,
                            events: [],
                        };
                    }
                    eventsInADay.events.push(event);
                });
                if (eventsInADay && eventsInADay.events.length > 0)
                    eventsByDay.push(eventsInADay);

                return eventsByDay;
            },
            generateEventsByMonthX(eventsByDay) {
                let eventsByMonth = [];
                let eventsInAMonth = {
                    monthYear: "",
                    eventsByDay: [],
                };
                eventsInAMonth = null;
                let currentMonthYear = "";
                eventsByDay.forEach(eventsInADay => {

                    if (currentMonthYear !== eventsInADay.monthYear) {
                        currentMonthYear = eventsInADay.monthYear;
                        if (eventsInAMonth) eventsByMonth.push(eventsInAMonth);
                        eventsInAMonth = {
                            monthYear: eventsInADay.monthYear,
                            eventsByDay: [],
                        };
                    }
                    eventsInAMonth.eventsByDay.push(eventsInADay);
                });
                if (eventsInAMonth && eventsInAMonth.eventsByDay.length > 0)
                    eventsByMonth.push(eventsInAMonth);

                return eventsByMonth;
            },
            generateEventsByMonth(events) {
                let eventsByMonth = [];
                let eventsInAMonth = {
                    monthYear: "",
                    events: [],
                };
                eventsInAMonth = null;
                let currentMonthYear = "";
                events.forEach(event => {

                    if (currentMonthYear !== event.EventDateMonthYear) {
                        currentMonthYear = event.EventDateMonthYear;
                        if (eventsInAMonth) eventsByMonth.push(eventsInAMonth);
                        eventsInAMonth = {
                            monthYear: event.EventDateMonthYear,
                            events: [],
                        };
                    }
                    eventsInAMonth.events.push(event);
                });
                if (eventsInAMonth && eventsInAMonth.events.length > 0)
                    eventsByMonth.push(eventsInAMonth);

                return eventsByMonth;
            },
        },
        computed: {
            eventsByMonth() {
                let events = this.events;

                if (!events) return events;

                let searchString = this.quickSearchQuery.trim();
                if (searchString) {
                    searchString = searchString.replace(/ +(?= )/g, '');
                    events = events.filter(event => {

                        let matchString = searchString.toLowerCase()
                            .split(" ").map(str => '(?=.*\\b' + str + '\\b)').join('');
                        return Object.values(event).join(" ").toLowerCase()
                            .match(matchString);

                    });
                }

                let eventsByDay = this.generateEventsByDay(events)
                let eventsByMonth = this.generateEventsByMonthX(eventsByDay);

                return eventsByMonth;
            },
        },

        mounted() {
            this.no_controls = this.nocontrols;
            this.one_column = this.onecolumn;
            this.getRecords();
            // this.getLastEventDate();
        },
    }
</script>

<style scoped>

    .is-narrow-button {
        height: 1.25em;
    }

    .level {
        margin-bottom: 0.5rem;
        /*background-color: #e7f4fd;*/
    }

    .button {
        line-height: 1rem;
    }

    .columns {
        margin-left: 0;
    }

    .month-year-tag {
        background-color: #F7F7F7;
        border-width: 1px 1px 1px 0;
        border-style: solid;
        border-color: #DDD;
        margin-bottom: 1rem;
        width: 100%;
        padding: 5px;
        white-space: nowrap;
        font-weight: bold;
    }

    .month-day-tag {
        background-color: #F7F7F7;
        border-width: 1px 1px 1px 0;
        border-style: solid;
        border-color: #DDD;
        margin-bottom: 0rem;
        padding-left: 1rem;
        width: fit-content;
        padding: 5px;
        white-space: nowrap;
        font-weight: bold;
    }

    .current-month {
        padding: 0 1rem 0.5rem 0;
    }

    .current-day{
        padding: 0 1rem 0.5rem 0;
    }

    .loading {
        margin: 3rem;
    }

</style>
