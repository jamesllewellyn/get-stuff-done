import Vuex from 'vuex';
import User from '../core/User';
import Team from '../core/Team';
const store = new Vuex.Store({
    state: {
        user : new User(),
        team: new Team({name:''}),
        formErrors: {},
    },
    actions: {
        /***********************
         * User Actions
         **********************/

        /***********************
         * Team Actions
         **********************/

    },
    mutations: {
        /***********************
         * User Mutations
         **********************/
        /***********************
         * Team Mutations
         **********************/
        CREATE_NEW_TEAM_SUCCESS: (state, {team}) => {
            /** send use into app */
            window.location.href = '/home';
        },
        CREATE_NEW_TEAM_FAILURE: (state, {errors}) => {
            /** add form errors */
            state.formErrors = errors;
        },
    },
    getters: {
        /***********************
         * Error Getters
         **********************/
        /** gets form errors by field name**/
        getFormErrors: (state, getters) => (fieldName) => {
            if (state.formErrors[fieldName]) {
                return state.formErrors[fieldName][0];
            }
        }
    }
});
export default store