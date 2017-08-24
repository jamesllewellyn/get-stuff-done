<template>
    <div id="my-tasks">
        <div class="level header is-mobile">
            <div class="level-left">
                <h1 class="title">Inbox</h1>
            </div>
            <div class="level-right">
                <div class="has-text-right">
                    <span class="tag is-orange is-medium">
                        <a  @click.prevent.stop="clearInbox" class="orange">Clear Inbox</a>
                    </span>
                </div>
            </div>
        </div>
        <hr />
        <transition-group name="fade" mode="out-in" >
            <div class="columns is-mobile is-centered" key="1" v-if="Object.keys(notifications).length === 0" v-cloak>
                <div class="column is-half">
                    <div class="box is-empty-inbox">
                        <div class="content has-text-centered">
                            <h3 class="bold">
                                You're all up to date
                            </h3>
                            <p>Your Inbox is currently empty</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="columns" v-for="(day, key ) in notifications" :key="key" v-else v-cloak>
                <h3 class="h3 column is-one-quarter" v-text="convertDate(key)" ></h3>
                <div class="column is-half">
                    <transition-group name="fade">
                        <inbox-item v-for="(inboxItem, key) in day" :inboxItem="inboxItem" :key="key"></inbox-item>
                    </transition-group>
                </div>
            </div>
        </transition-group>
    </div>
</template>

<script>
    import store from '../../store';
    import InboxItem from '../../components/InboxItem.vue';
    export default {
        data() {
            return{

            }
        },
        components: {
            InboxItem
        },
        computed: {
            user: function(){
                /**
                 * user state is blank class if user is not set
                 * check email is set to make sure user data is present
                 * */
                if(!store.state.user.email){
                    /** return false if not user data */
                    return false;
                }
                return store.state.user;
            },
            notifications: function(){
                return store.getters.getNofificationsByDays;
            }
        },
        methods: {
            /** clear mark all notifications as read */
            clearInbox: function(){
                this.$store.dispatch('USER_CLEAR_INBOX');
            },
            /** trigger toggle modal event */
            triggerEvent: function(eventName, payload){
                Event.$emit(eventName, payload);
            },
            convertDate:function(date){
              return moment(date).calendar(null,{
                  lastDay : '[Yesterday]',
                  sameDay : '[Today]',
                  nextDay : '[Tomorrow]',
                  lastWeek : '[last] dddd',
                  nextWeek : 'dddd',
                  sameElse : 'L'
              });
            }
        },
        watch: {
            user () {
                /** Wait for user data before fetching user inbox **/
                if(this.user){
                    this.$store.dispatch('GET_NOTIFICATIONS');
                }
            },
        },
        mounted: function () {
            /** we have user data dispatch call to get users notifications */
            if(this.user){
                this.$store.dispatch('GET_NOTIFICATIONS');
            }
        }
    }
</script>
