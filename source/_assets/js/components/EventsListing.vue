<template>


    <div>

        <div class="field is-grouped is-grouped-centered">

            <div class="field has-addons">
                <div class="control has-icons-left">
                    <input class="input is-narrow" type="text" placeholder="Quick Search"
                           v-model="quickSearchQuery">
                    <span class="icon is-small is-left">
                      <i class="fas fa-search"></i>
                    </span>
                </div>

                <p class="control">
                    <a class="button is-info" @click.prevent="clearQuickSearch()">
                        Clear
                    </a>
                </p>
            </div>
            <div class="field">
                <p class="control">
                    <a class="button is-info" @click.prevent="toggleAll();">
                        {{showHideLabel()}}<br>Details
                    </a>
                </p>
            </div>
        </div>


        <div class="has-text-centered loading" v-if="loading">
            <a class="button is-info is-loading">
                Loading
            </a>
        </div>


        <div class="level has-text-centered is-info">
            <div class="level-left">
                <a class="button is-info is-narrow-button" @click="decrementDisplayPeriod()">
                    <span class="icon"><i class="far fa-hand-point-left"></i></span>
                    <span>Previous</span>
                </a>
            </div>
            <div class="level-item">
                <strong>
                    {{this.startOfDisplayPeriod.format("MMM YYYY")}} -
                    {{this.endOfDisplayPeriod.format("MMM YYYY")}}
                </strong>
            </div>
            <div class="level-right">
                <a class="button is-info is-narrow-button" @click="incrementDisplayPeriod()">
                    <span>Next</span>
                    <span class="icon"><i class="far fa-hand-point-right"></i></span>
                </a>
            </div>
        </div>

        <div class="notification is-info has-text-centered" v-if="norecords">
            No records found.
        </div>

        <div class="columns is-multiline">
            <div v-for="eventsInMonth in eventsByMonth" :key="eventsInMonth.monthYear"
                 class="box current-month column is-half">

                <div class="month-year-tag">{{ eventsInMonth.monthYear }}</div>

                <div v-for="event in eventsInMonth.events" :key="event.uniqueId">
                    <event-container :event="event"></event-container>
                </div>

            </div>
        </div>
    </div>


</template>

<script>

    import queryString from 'query-string'

    export default {
        name: "events-listing",
        props: ['endpoint'],
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
            }
        },

        methods: {
            getRecords() {
                axios.get("https://dev.aactmad.org/eventServiceJson.php" + "?" + this.getQueryParameters())
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
                let queryParms =
                    {
                        startCalendar: this.startOfDisplayPeriod.format("MM/DD/YYYY"),
                        endCalendar: this.endOfDisplayPeriod.format("MM/DD/YYYY"),
                    };

                return queryString.stringify(queryParms)
            },
            getLastEventDate() {
                axios.get("https://dev.aactmad.org/eventServiceJson.php")
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
                    let eventStartDate = moment(event.EventStart);
                    event.uniqueId = event.EventID + "." + event.ReID;
                    // event.EventPrettyDate = eventStartDate.toString("ddd MMM dd, yyyy");
                    event.EventDateMonthYear = eventStartDate.format("MMMM YYYY");
                    event.EventDateDayofWeek = eventStartDate.format("ddd");
                    event.EventDateMonth = eventStartDate.format("MMM");
                    event.EventDateMonthDay = eventStartDate.format("MMM DD");
                    event.EventDateMonthFull = eventStartDate.format("MMMM");
                    event.EventDateYear = eventStartDate.format("YYYY");

                    event.displayBody = this.showall;
                });
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

                let eventsByMonth = this.generateEventsByMonth(events);

                return eventsByMonth;
            },
        },

        mounted() {
            this.getRecords();
            this.getLastEventDate();
        },
    }
</script>

<style scoped>

    .is-narrow-button {
        height: 1.25em;
    }

    .level {
        margin-bottom: 0.5rem;
        background-color: #e7f4fd;
    }

    .button {
        line-height: 1rem;
    }

    .columns {
        margin-left: 0rem;
    }

    .month-year-tag {
        background-color: #F7F7F7;
        border-width: 1px 1px 1px 0;
        border-style: solid;
        border-color: #DDD;
        margin-bottom: 1rem;
        width: fit-content;
        padding: 5px;
        white-space: nowrap;
        font-weight: bold;
    }

    .current-month {
        padding: 0rem 1rem 0.5rem 0rem;
    }

    .loading {
        margin: 3rem;
    }

</style>