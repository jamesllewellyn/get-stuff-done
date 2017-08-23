import Vuex from 'vuex';
import Project from '../core/Project';
import Section from '../core/Section';
import Task from '../core/Task';
import Team from '../core/Team';
import User from '../core/User';
const store = new Vuex.Store({
    state: {
        teams: [],/** all users teams */
        projects: [],/** all current teams projects */
        project: null,/** all current projects sections and tasks */
        task: null,/** Current task being displayed */
        notifications: {},/** Users notifications */
        myTasks: {},/** all tasks assigned to user */
        myOverDue: {},/** all users tasks currently overDue */
        myWorkingOnIt: {},/** all users tasks flagged as working on it */
        user: new User({first_name: '', last_name : '', handle : '', email : '', password : ''}),/** user */
        formErrors: {},/** current form errors */
        modals:[],/** all app models */
        navVisible: false,/** is mobile navigation visible flag */
        profileVisible: false,/** is profile visible flag */
        switchTeamVisible:false,/** is switch team visible flag */
        isLoading:true/** AJAX spinning loader flag */
    },
    actions: {
        /***********************
         * Sign Up Actions
         **********************/
        REGISTER_USER:function ({ commit }, user) {
            axios.post('/register', user)
                .then((response) => {
                console.log(response);
                    commit('REGISTER_USER_PASS', { user: response.data.user})
                }, (error) => {
                    if(error.response.data){
                        console.log(error.response);
                        commit('REGISTER_USER_FAIL', { errors:  error.response.data });
                        return false;
                    }
                    commit('SERVER_ERROR');
                });
        },
        SIGN_UP_SUBMIT :function ({ commit, state }, team) {
            axios.post('/api/user/'+state.user.id+'/team/' ,team )
                .then((response) => {
                    commit('SIGN_UP_SUCCESS', { user: response.data.user})
                }, (error) => {
                    if(error.response.data){
                        commit('SIGN_UP_FAIL', { errors:  error.response.data });
                        return false;
                    }
                    commit('SERVER_ERROR');
                });
        },
        /***********************
         * User Actions
         **********************/
        LOAD_USER: function ({ commit }) {
            axios.get('/api/user')
            .then((response) => {
                commit('SET_USER', { user: response.data })
            }, (err) => {
                commit('SERVER_ERROR');
            });
        },
        STORE_USER: function ({ commit }, user) {
            axios.post('/api/user/', user)
                .then((response) => {
                    commit('STORE_USER_SUCCESS', { user: response.data.user})
                }, (error) => {
                    if(error.response.data){
                        commit('STORE_USER_FAILURE', { errors:  error.response.data });
                        return false;
                    }
                    commit('SERVER_ERROR');
                });
        },
        UPDATE_USER: function ({ commit, state }, user) {
            axios.put('/api/user/'+state.user.id, user)
                .then((response) => {
                    commit('UPDATE_USER_SUCCESS', { user: response.data.user})
                }, (error) => {
                    if(error.response.data){
                        commit('UPDATE_USER_ERROR', { errors:  error.response.data });
                        return false;
                    }
                    commit('SERVER_ERROR');
                });
        },
        ADD_TEAM_MEMBER: function ({ commit, getters , state}, email) {
            axios.post('/api/team/'+getters.getActiveTeam.id+'/user', {email:email})
                .then((response) => {
                    /** clear button loading state */
                    commit('REMOVE_BUTTON_LOADING_STATE', {name : 'addUser'});
                    /** check for errors */
                    if(!response.data.success){
                        /** check for errors */
                        commit('ADD_TEAM_MEMBER_ERROR', { message:  response.data.message });
                        return false;
                    }
                    /** close modal */
                    commit('TOGGLE_MODAL_IS_VISIBLE', {name : 'addUser'});
                    commit('ADD_TEAM_MEMBER_SUCCESS', { message:  response.data.message, user: response.data.user});
                }, (error) => {
                    commit('SERVER_ERROR');
                });
        },
        ADD_USER_AVATAR:function({commit, state}, base64 ){
            axios.post('/api/user/'+state.user.id+'/avatar', {base64 : base64})
                .then(function (response) {
                    /** call success */
                    commit('ADD_USER_AVATAR_SUCCESS');
                    /** close modal */
                    commit('TOGGLE_MODAL_IS_VISIBLE', {name : 'uploadAvatar'});
                })
                .catch(function (error) {
                    commit('SERVER_ERROR');
                });
        },
        GET_MY_TASKS:function({commit, state} ){
            axios.get('/api/user/'+state.user.id+'/tasks')
                .then(function (response) {
                    /** call success */
                    commit('GET_MY_TASKS_SUCCESS', {tasks : response.data});
                })
                .catch(function (error) {
                    console.log(error);
                    commit('SERVER_ERROR');
                });
        },
        GET_MY_OVER_DUE:function({commit, state} ){
            axios.get('/api/user/'+state.user.id+'/over-due')
                .then(function (response) {
                    /** call success */
                    commit('GET_OVER_DUE_SUCCESS', {tasks : response.data});
                })
                .catch(function (error) {
                    commit('SERVER_ERROR');
                });
        },
        GET_MY_WORKING_ON_IT:function({commit, state} ){
            axios.get('/api/user/'+state.user.id+'/working-on-it')
                .then(function (response) {
                    /** call success */
                    commit('GET_WORKING_ON_IT_SUCCESS', {tasks : response.data});
                })
                .catch(function (error) {
                    commit('SERVER_ERROR');
                });
        },
        GET_NOTIFICATIONS:function({commit, state} ){
            axios.get('/api/user/'+state.user.id+'/notifications')
                .then(function (response) {
                    /** call success */
                    commit('GET_NOTIFICATIONS_SUCCESS', {notifications : response.data});
                })
                .catch(function (error) {
                    commit('SERVER_ERROR');
                });
        },
        NOTIFICATION_MARK_AS_READ:function({commit}, {id} ){
            axios.put('/api/notification/'+id)
                .then(function (response) {
                    /** call success get notifications success mutator to replace current notifications with remaining unread  */
                    commit('GET_NOTIFICATIONS_SUCCESS', {notifications : response.data.notifications});
                })
                .catch(function (error) {
                    commit('SERVER_ERROR');
                });
        },
        USER_CAN_ACCESS_TEAM:function({commit}, {teamId}){
            axios.get('/api/team/'+teamId+'/can-access')
                .then(function (response) {
                    /** call success */
                    commit('SWITCH_TEAM_SUCCESS', {teamId : teamId});
                })
                .catch(function (error) {
                    commit('SERVER_ERROR');
                });
        },
        USER_CAN_ACCESS_PROJECT:function({commit}, {teamId, projectId}){
            axios.get('/api/team/'+teamId+'/project/'+projectId+'/can-access')
                .then(function (response) {
                    if(!response.data.success){
                        commit('SERVER_ERROR');
                    }
                    /** call success */
                    commit('TAKE_USER_TO_PROJECT', {teamId : teamId, projectId:projectId} );
                })
                .catch(function (error) {
                    commit('SERVER_ERROR');
                });
        },
        USER_CAN_ACCESS_TASK:function({commit}, {teamId, projectId, sectionId, taskId}){
            axios.get('/api/team/'+teamId+'/project/'+projectId+'/section/'+sectionId+'/task/'+taskId+'/can-access')
                .then(function (response) {
                    if(!response.data.success){
                        commit('SERVER_ERROR');
                    }
                    /** call success */
                    commit('TAKE_USER_TO_TASK', {teamId : teamId, projectId:projectId, sectionId : sectionId,  task:response.data.task} );
                })
                .catch(function (error) {
                    commit('SERVER_ERROR');
                });
        },
        /***********************
         * Team Actions
         **********************/
        LOAD_TEAMS: function({commit ,state}){
            axios.get('/api/user/' + state.user.id + '/teams')
                .then((response) => {
                    commit('SET_TEAM_LIST', { teams: response.data });
                    /** clear ajax loader **/
                    commit('CLEAR_IS_LOADING');
                }, (err) => {
                    commit('SERVER_ERROR');
                })
        },
        ADD_NEW_TEAM: function ({ commit, state }, {team}) {
            axios.post('/api/user/'+state.user.id+'/team/' ,team )
                .then((response) => {
                    commit('ADD_NEW_TEAM_SUCCESS', { team: response.data.team});
                    /** clear button loading state*/
                    commit('REMOVE_BUTTON_LOADING_STATE', {name : 'addTeam'});
                    /** close modal */
                    commit('TOGGLE_MODAL_IS_VISIBLE', {name : 'addTeam'});
                }, (error) => {
                    if(error.response.data){
                        commit('ADD_NEW_TEAM_FAILURE', { errors:  error.response.data });
                        return false;
                    }
                    commit('SERVER_ERROR');
                });
        },
        UPDATE_TEAM: function({ commit, getters }, {team}){
            axios.put('/api/team/'+ getters.getActiveTeam.id, team )
                .then((response) => {
                    commit('UPDATE_TEAM_SUCCESS', { team: response.data.team})
                }, (error) => {
                    if(error.response.data){
                        commit('UPDATE_TEAM_FAILURE', { errors:  error.response.data });
                        return false;
                    }
                    commit('SERVER_ERROR');
                });
        },
        SWITCH_TEAM: function({ commit, getters, state }, {teamId}){
            axios.put('/api/user/'+ state.user.id+'/team', {teamId :teamId} )
                .then((response) => {
                    commit('SWITCH_TEAM_SUCCESS', { teamId: teamId})
                }, (error) => {
                    if(error.response.data){
                        commit('UPDATE_TEAM_FAILURE', { errors:  error.response.data });
                        return false;
                    }
                    commit('SERVER_ERROR');
                });
        },
        /***********************
         * Project Actions
         **********************/
        GET_PROJECT:  function ({ commit, getters}, {id}) {
            axios.get('/api/team/' + getters.getActiveTeam.id + '/project/' + id )
                .then((response) => {
                    commit('SET_PROJECT', { project: response.data.project });
                }, (err) => {
                    commit('SERVER_ERROR');
            })
        },
        ADD_NEW_PROJECT: function ({ commit ,getters} , project) {
            axios.post('/api/team/'+getters.getActiveTeam.id+'/project/', project)
            .then(function (response) {
                commit('ADD_PROJECT_SUCCESS', {project: response.data.project });
                /** clear button loading state */
                commit('REMOVE_BUTTON_LOADING_STATE', {name : 'addProject'});
                /** close modal */
                commit('TOGGLE_MODAL_IS_VISIBLE', {name : 'addProject'});
            })
            .catch(function (error) {
                if(error.response.data){
                    commit('ADD_PROJECT_FAILURE', { errors:  error.response.data });
                }
                /** clear button loading state*/
                commit('REMOVE_BUTTON_LOADING_STATE', {name : 'addProject'});
            });
        },
        UPDATE_PROJECT: function ({ commit, getters } ,{id, project}) {
            axios.put('/api/team/'+getters.getActiveTeam.id+'/project/' + id, project)
                .then(function (response) {
                    /**  **/
                    commit('UPDATE_PROJECT_SUCCESS', {project: response.data.project });
                })
                .catch(function (error) {
                   commit('UPDATE_PROJECT_FAILURE');
                });
        },
        /***********************
         * Section Actions
         **********************/
        ADD_NEW_SECTION: function ({ commit, getters } ,{projectId, section}) {
            axios.post('/api/team/'+getters.getActiveTeam.id+'/project/'+ projectId +'/section/', section)
            .then(function (response) {
                /**  **/
                commit('ADD_SECTION_SUCCESS', {section: response.data.section });
                /** clear button loading state*/
                commit('REMOVE_BUTTON_LOADING_STATE', {name : 'addSection'});
                /** close modal */
                commit('TOGGLE_MODAL_IS_VISIBLE', {name : 'addSection'});
            })
            .catch(function (error) {
                if(error.response.data){
                    commit('ADD_SECTION_FAILURE', { errors:  error.response.data });
                }
                /** clear button loading state*/
                commit('REMOVE_BUTTON_LOADING_STATE', {name : 'addSection'});
            });
        },
        UPDATE_SECTION_TASKS_SORT_ORDER: function ({ commit, state, getters } ,{section, tasks}) {
            axios.put('/api/team/'+getters.getActiveTeam.id+'/project/'+state.project.id+'/section/'+ section.id + '/tasks/reorder', {tasks : tasks})
                .then(function (response) {
                    commit('UPDATE_SECTION_TASKS_SORT_ORDER_SUCCESS', { section: section, tasks : tasks});
                })
                .catch(function (error) {
                    commit('SERVER_ERROR');
                });
        },
        UPDATE_SECTION: function ({ commit, state, getters } ,{id, section}) {
            axios.put('/api/team/'+getters.getActiveTeam.id+'/project/'+ state.project.id +'/section/' + id, section)
                .then(function (response) {
                    /**  **/
                    commit('UPDATE_SECTION_SUCCESS', { section: response.data.section });
                })
                .catch(function (error) {
                    commit('UPDATE_SECTION_FAILURE');
                });
        },
        /***********************
         * Task Actions
         **********************/
        GET_TASK: function ({ commit, state, getters } ,{projectId , sectionId, id}) {
            axios.get('/api/team/'+getters.getActiveTeam.id+'/project/'+ projectId +'/section/' + sectionId + '/task/' + id)
                .then(function (response) {
                    console.log(response);
                    /**  **/
                    commit('GET_TASK_SUCCESS', {task: response.data.task });
                })
                .catch(function (error) {
                    commit('SERVER_ERROR');
                });
        },
        ADD_NEW_TASK: function ({ commit, state, getters } ,{sectionId, task}) {
            axios.post('/api/team/'+getters.getActiveTeam.id+'/project/'+ state.project.id +'/section/' + sectionId + '/task', task)
                .then(function (response) {
                    /**  **/
                    commit('ADD_TASK_SUCCESS', { sectionId: sectionId,  task: response.data.task });
                    /** clear button loading state*/
                    commit('REMOVE_BUTTON_LOADING_STATE', {name : 'addTask'});
                    /** close modal */
                    commit('TOGGLE_MODAL_IS_VISIBLE', {name : 'addTask'});
                })
                .catch(function (error) {
                    if(error.response.data){
                        commit('ADD_TASK_FAILURE', { errors:  error.response.data });
                    }
                    /** clear button loading state*/
                    commit('REMOVE_BUTTON_LOADING_STATE', {name : 'addTask'});
                });
        },
        UPDATE_TASK: function ({ state, commit, getters} ,{projectId, sectionId, id, task}) {
            axios.put('/api/team/'+getters.getActiveTeam.id+'/project/'+ projectId +'/section/' + sectionId + '/task/' + id, task)
                .then(function (response) {
                    /**  **/
                    console.log( response.data.task );
                    commit('UPDATE_TASK_SUCCESS', {sectionId: sectionId, task: response.data.task });
                })
                .catch(function (error) {
                    console.log( error );
                    if(error.response.data){
                        commit('UPDATE_TASK_FAILURE', { errors:  error.response.data });
                    }
                    /** clear button loading state*/
                    commit('REMOVE_BUTTON_LOADING_STATE', {name : 'updateTask'});
                });
        },
    },
    mutations: {
        /***********************
         * Sign up Mutations
         **********************/
        REGISTER_USER_PASS: (state, { user }) => {
            /** add user */
            state.user = user;
            Event.$emit('create-team-page','set-up-team');
        },
        REGISTER_USER_FAIL: (state, { errors }) => {
            /** add form errors */
            state.formErrors = errors;
        },
        SIGN_UP_SUCCESS: (state) => {
            /** take your to homepage */
            window.location = '/home';
        },
        SIGN_UP_FAIL: (state) => {
            /** take user to app route */
            window.location = '/';
        },
        /***********************
         * User Mutations
         **********************/
        SET_USER: (state, { user }) => {
            state.user = user;
        },
        ADD_USER_AVATAR_SUCCESS: (state) => {
            Event.$emit('notify','success', 'Success', 'Your avatar has been updated');
        },
        UPDATE_USER_SUCCESS: (state,  { user }) => {
            state.user = user;
        },
        UPDATE_USER_ERROR: (state,  { errors }) => {
            /** add form errors */
            state.formErrors = errors;
        },
        GET_MY_TASKS_SUCCESS:(state, {tasks}) => {
            /** group tasks into projects */
            let groupedProjects = _.groupBy(tasks, 'section.project_id');
            _.forEach(groupedProjects, function(project, key) {
                /** group tasks in projects into sections */
                groupedProjects[key] = _.values( _.groupBy(project, 'section_id') );
            });
            state.myTasks = groupedProjects;
        },
        GET_OVER_DUE_SUCCESS:(state, {tasks}) => {
            /** group tasks into projects */
            let groupedProjects = _.groupBy(tasks, 'section.project_id');
            _.forEach(groupedProjects, function(project, key) {
                /** group tasks in projects into sections */
                groupedProjects[key] = _.values( _.groupBy(project, 'section_id') );
            });
            state.myOverDue = groupedProjects;
        },
        GET_WORKING_ON_IT_SUCCESS:(state, {tasks}) => {
            /** group tasks into projects */
            let groupedProjects = _.groupBy(tasks, 'section.project_id');
            _.forEach(groupedProjects, function(project, key) {
                /** group tasks in projects into sections */
                groupedProjects[key] = _.values( _.groupBy(project, 'section_id') );
            });
            state.myWorkingOnIt = groupedProjects;
        },
        GET_NOTIFICATIONS_SUCCESS:(state, {notifications}) => {
            /** group notifactions into days */
            let groupedDays = _.groupBy(notifications, (notification) => moment(notification['created_at'], 'YYYY-MM-DD').calendar(moment('YYYY-MM-DD')));
            state.notifications = groupedDays;
        },
        NOTIFICATION_MARK_AS_READ_SUCCESS:(state, {notificationId}) => {
            /** group notifactions into days */

        },
        TAKE_USER_TO_PROJECT:(state, {teamId, projectId}) => {
            /** parse id to int */
            let tId = parseInt(teamId);
            /** update users current_team_id */
            state.user.current_team_id = tId;
            /** take user to project page */
            Event.$emit('changePage', '/project/'+projectId);
        },
        TAKE_USER_TO_TASK:(state, {teamId, projectId, sectionId, task}) => {
            /** parse id to int */
            let tId = parseInt(teamId);
            /** update users current_team_id */
            state.user.current_team_id = tId;
            /** take user to project page */
            Event.$emit('changePage', '/project/'+projectId);
            /** set task as current task */
            state.task = new Task(task);
            /** show tasks*/
            Event.$emit('showTask',projectId, sectionId,task.id);
        },
        /***********************
         * Team Mutations
         **********************/
        SET_TEAM_LIST: (state, { teams }) => {
            /** clear current teams **/
            state.teams = [];
            /** loop each new team and push to state.teams **/
            teams.forEach(function(team){
                state.teams.push( new Team(team));
            });
        },
        SET_ACTIVE_TEAM: (state, { team }) => {
            state.user.current_team_id = team.id;
        },
        ADD_NEW_TEAM_SUCCESS: (state, {team}) => {
            /** add team */
            state.teams.push( new Team(team));
            /** set team as current team */
            state.teams.forEach(function(t){
                /** false active as false */
                t.active = false;
                /** if ids matches false as active */
                if(t.id === team.id){
                    t.active = true;
                }
            });
        },
        ADD_NEW_TEAM_FAILURE: (state, {errors}) => {
            /** add form errors */
            state.formErrors = errors;
        },
        ADD_TEAM_MEMBER_SUCCESS: (state, {message, user}) => {
            /** get current team index **/
            let tIdx = state.teams.map(team => team.active).indexOf(true);
            /** add new user to team object*/
            state.teams[tIdx].users.push(user);
            /** send user success message */
            Event.$emit('notify','success', 'Success', message);
        },
        ADD_TEAM_MEMBER_ERROR: (state, {message}) => {
            /** send user error message */
            Event.$emit('notify','error', 'Whoops', message);
        },
        ADD_TEAM_MEMBER_FAILURE: (state, {message}) => {
            /** send user error message */
            Event.$emit('notify','error', 'Whoops', message);
        },
        SWITCH_TEAM_SUCCESS: (state, {teamId}) => {
            /** parse id to int */
            let tId = parseInt(teamId);
            /** update users current_team_id */
            state.user.current_team_id = tId;
            /** get current team */
            let team = state.teams.find(team => team.id === state.user.current_team_id);
            /** take user to project in team */
            Event.$emit('changePage', '/project/'+team.projects[0].id);
            /** display notification to user */
            Event.$emit('notify','success', 'Success', 'Team has been switched');
        },
        UPDATE_TEAM_SUCCESS: (state, {team}) => {
            /** get current team index **/
            let tIdx = state.teams.map(team => team.id).indexOf(state.user.current_team_id);
            /** update team name **/
            state.teams[tIdx].name = team.name;
        },
        UPDATE_TEAM_FAILURE: (state) => {
            Event.$emit('notify','error', 'Whoops', 'Team name couldn\'t be updated');
        },
        /***********************
         * Project Mutations
         **********************/
        SET_PROJECT: (state, { project }) =>{
            state.project = project;
            let idx = 0 ;
            state.project.sections.forEach(function(section){
                state.project.sections[idx] = new Section(section);
                idx++;
            });
        },
        CLEAR_PROJECT:(state) =>{
            state.project = null;
        },
        ADD_PROJECT_SUCCESS: (state, { project }) => {
            /** get current team index **/
            let tIdx = state.teams.map(team => team.id).indexOf(state.user.current_team_id);
            /** add new project to data array*/
            state.teams[tIdx].projects.push(project);
            /** notify user of success **/
            Event.$emit('notify','success', 'Success', 'New project has been created');
        },
        ADD_PROJECT_FAILURE: (state, { errors }) => {
            /** add form errors */
            state.formErrors = errors;
        },
        UPDATE_PROJECT_SUCCESS: (state, {project}) => {
            /** get current team index **/
            let tIdx = state.teams.map(team => team.id).indexOf(state.user.current_team_id);
            /** get project index **/
            let pIdx = state.teams[tIdx].projects.map(p => p.id).indexOf(project.id);
            /** update project name **/
            state.teams[tIdx].projects[pIdx].name = project.name;
            /** notify user of success **/
            Event.$emit('notify','success', 'Success', 'Project name has been updated');
        },
        UPDATE_PROJECT_FAILURE: (state) => {
            Event.$emit('notify','error', 'Whoops', 'Project name couldn\'t be updated');
        },
        /***********************
         * Section Mutations
         **********************/
        ADD_SECTION_SUCCESS: (state, { section }) => {
            /** add section to currently loaded project **/
            state.project.sections.push( new Section(section) );
            /** notify user of success **/
            Event.$emit('notify','success', 'Success', 'New section has been created');
        },
        ADD_SECTION_FAILURE: (state, { errors }) => {
            /** add form errors */
            state.formErrors = errors;
        },
        UPDATE_SECTION_SUCCESS: (state, { section }) => {
            /** get section index **/
            let sIdx = state.project.sections.map(section => section.id).indexOf(section.id);
            /** update project name **/
            state.project.sections[sIdx].name = section.name;
            /** notify user of success **/
            Event.$emit('notify','success', 'Success', 'Section name has been updated');
        },
        UPDATE_SECTION_FAILURE: (state) => {
            Event.$emit('notify','error', 'Whoops', 'Section name couldn\'t be updated');
        },
        /***********************
         * Task Mutations
         **********************/
        CLEAR_TASK:(state) =>{
            state.task = null;
        },
        GET_TASK_SUCCESS: (state, { task }) => {
            /** add task to active task state **/
            state.task = new Task(task);
        },
        ADD_TASK_SUCCESS: (state, {sectionId, task }) => {
            /** cast id to int **/
            let sId = parseInt(sectionId);
            /** get project index **/
            let sIdx = state.project.sections.map(section => section.id).indexOf(sId);
            /** add new task to data array **/
            state.project.sections[sIdx].tasks.push( new Task(task) );
            /** notify user of success **/
            Event.$emit('notify','success', 'Success', 'New task has been added');
        },
        ADD_TASK_FAILURE: (state, { errors }) => {
            /** add form errors */
            state.formErrors = errors;
        },
        UPDATE_TASK_SUCCESS: (state, { sectionId, task }) => {
            /** cast id to int **/
            let sId = parseInt(sectionId);
            let tId = parseInt(task.id);
            let sIdx = state.project.sections.map(section => section.id).indexOf(sId);
            console.log('sIdx: '+sIdx);
            let tIdx = state.project.sections[sIdx].tasks.map(tasks => task.id).indexOf(tId);
            console.log('tIdx: '+tIdx);
            /** update task to data array **/
            state.project.sections[sIdx].tasks[tIdx] = new Task(task);
        },
        UPDATE_TASK_FAILURE: (state, { errors }) => {
            /** add form errors */
            state.formErrors = errors;
        },
        UPDATE_SECTION_TASKS_SORT_ORDER_SUCCESS: (state, { section, tasks }) => {
            /** get section index **/
            let sIdx = state.project.sections.map(section => section.id).indexOf(section.id);
            /** reorder tasks by sort_order and update project object **/
            state.project.sections[sIdx].tasks = _.sortBy(tasks, function(task) { return task.sort_order; });
        },
        /***********************
         * Modal Mutations
         **********************/
        ADD_MODAL: (state, { name }) => {
            state.modals.push({name : name, isVisible : false, isLoading : false});
        },
        TOGGLE_MODAL_IS_VISIBLE: (state,{name}) =>{
            let idx = state.modals.map(modal => modal.name).indexOf(name);
            state.modals[idx].isVisible = !state.modals[idx].isVisible;
            /** clear all form errors **/
            state.formErrors = '';
        },
        SET_BUTTON_TO_LOADING: (state,{name}) =>{
            let idx = state.modals.map(modal => modal.name).indexOf(name);
            state.modals[idx].isLoading = true;
        },
        REMOVE_BUTTON_LOADING_STATE: (state,{name}) =>{
            let idx = state.modals.map(modal => modal.name).indexOf(name);
            state.modals[idx].isLoading = false;
        },
        /***********************
         * Nav Mutations
         **********************/
        TOGGLE_NAV_IS_VISIBLE: (state) =>{
            state.navVisible = !state.navVisible;
        },
        /***********************
         * Profile Mutations
         **********************/
        TOGGLE_PROFILE_IS_VISIBLE:(state)=>{
            state.profileVisible = !state.profileVisible;
        },
        /***********************
         * Switch Team Mutations
         **********************/
        TOGGLE_SWITCH_TEAM_IS_VISIBLE:(state)=>{
            state.switchTeamVisible = !state.switchTeamVisible;
        },
        /***********************
         * Errors Mutations
         **********************/
        SERVER_ERROR:()=>{
            Event.$emit('notify','error', 'Whoops', 'Sorry somethings gone wrong here');
        },
        /***********************
         * AJAX loader Mutations
         **********************/
        SET_IS_LOADING:(state)=>{
            state.isLoading = true;
        },
        CLEAR_IS_LOADING:(state)=>{
            state.isLoading = false;
        },
    },
    getters: {
        /***********************
         * Team Getters
         **********************/
        /** returns current teams */
        getActiveTeam: (state) => {
            if(!state.teams){
                return false;
            }
            return  state.teams.find(team => team.id === state.user.current_team_id);
        },
        getTeamUser:(state, getters) => {
            if(!getters.getActiveTeam){
                return false;
            }
            return getters.getActiveTeam.users;
        },
        /***********************
         * Project Getters
         **********************/
        /** returns current teams projects */
        getProjects: (state, getters) => {
            if(!getters.getActiveTeam){
                return false;
            }
            return getters.getActiveTeam.projects;
        },
        /** returns current project */
        getProject: (state) => {
            if(!state.project){
                return false;
            }
            return state.project;
        },
        getProjectById: (state, getters) => (projectId) => {
            /** cast id to int **/
            let id = parseInt(projectId);
            return state.projects.find(project => project.id === id)
        },
        /***********************
         * Section Getters
         **********************/
        /** returns all project sections flattered into one array **/
        getSections: (state, getters) => {
            if(state.project){
                return _.flatten( state.project.sections);
            }
            return false;
        },
        getSectionById: (state, getters) => ({sectionId}) => {
            /** cast id to int **/
            let sId = parseInt(sectionId);
            /** find and return section **/
            return state.project.sections.find(section => section.id === sId);
        },
        /***********************
         * Task Getters
         **********************/
        /** returns current task */
        getTask: (state) => {
            if(!state.task){
                return false;
            }
            return state.task;
        },
        /** returns a task **/
        getTaskById: (state, getters) => ({ sectionId, id}) => {
            /** cast ids to int **/
            let pId = parseInt(projectId);
            let sId = parseInt(sectionId);
            let tId = parseInt(id);
            /** find object index of project **/
            let pIdx = state.projects.map(project => project.id).indexOf(pId);
            /** find object index of section **/
            let sIdx = state.projects[pIdx].sections.map(section => section.id).indexOf(sId);
            /** find and return task **/
            return state.projects[pIdx].sections[sIdx].tasks.find(task => task.id === tId);
        },
        /** all users tasks  **/
        getMyTasks: (state) => {
            return state.myTasks;
        },
        /** all users over due tasks **/
        getMyOverDue: (state) => {
            return state.myOverDue;
        },
        /** all users tasks flagged as working on it **/
        getMyWorkingOnIt: (state) => {
            return state.myWorkingOnIt;
        },
        /** filters getTasks() and returns tasks which deadlines are within the next week **/
        getUpComing: (state, getters) => {
           // let upComing = [];
           // let now = moment();
           // let nextWeek = moment().add(7, 'days');
           // return _.filter(getters.getTasks,  function(task) { return moment(task.due_date).isBetween(now, nextWeek) && task.status_id === null; });
        },
        /***********************
         * Modal Getters
         **********************/
        /** returns isVisible and isLoading states for modal my name **/
        getModalByName: (state, getters) => (name) => {
            return state.modals.find(modal => modal.name === name)
        },
        /** gets form errors by field name**/
        getFormErrors: (state, getters) => (fieldName) => {
            if (state.formErrors[fieldName]) {
                return state.formErrors[fieldName][0];
            }
        }
    }
});
export default store