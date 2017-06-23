import Vuex from 'vuex';
import Project from '../core/Project';
import Section from '../core/Section';
import Task from '../core/Task';
const store = new Vuex.Store({
    state: {
        projects: [],
        user:{},
        formErrors: {},
        modals:[]
    },
    actions: {
        /***********************
         * User Actions
         **********************/
        LOAD_USER: function ({ commit }) {
            axios.get('/api/user')
            .then((response) => {
                commit('SET_USER', { user: response.data })
            }, (err) => {
                console.log(err)
            });
        },
        /***********************
         * Project Actions
         **********************/
        LOAD_PROJECT_LIST: function ({ commit }) {
            axios.get('/api/user/' + '1' + '/projects')
            .then((response) => {
                commit('SET_PROJECT_LIST', { projects: response.data });
            }, (err) => {
                console.log(err)
            })
        },
        ADD_NEW_PROJECT: function ({ commit } ,project) {
            axios.post('/api/project/', project)
            .then(function (response) {
                commit('ADD_PROJECT_SUCCESS', { project: response.data.project });
                /** clear button loading state*/
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
        /***********************
         * Section Actions
         **********************/
        ADD_NEW_SECTION: function ({ commit } ,{projectId, section}) {
            axios.post('/api/project/'+ projectId +'/section/', section)
            .then(function (response) {
                /**  **/
                commit('ADD_SECTION_SUCCESS', { projectId: projectId, section: response.data.section });
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
        /***********************
         * Task Actions
         **********************/
        ADD_NEW_TASK: function ({ commit } ,{projectId, sectionId, task}) {
            axios.post('/api/project/'+ projectId +'/section/' + sectionId + '/task', task)
                .then(function (response) {
                    /**  **/
                    commit('ADD_TASK_SUCCESS', { projectId: projectId, sectionId: sectionId,  task: response.data.task });
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
        UPDATE_TASK: function ({ commit } ,{projectId, sectionId, id, task}) {
            axios.put('/api/project/'+ projectId +'/section/' + sectionId + '/task/' + id, task)
                .then(function (response) {
                    /**  **/
                    commit('UPDATE_TASK_SUCCESS', { projectId: projectId, sectionId: sectionId, id: id, task: response.data.task });
                    /** clear button loading state*/
                    commit('REMOVE_BUTTON_LOADING_STATE', {name : 'updateTask'});
                    /** close modal */
                    commit('TOGGLE_MODAL_IS_VISIBLE', {name : 'updateTask'});
                })
                .catch(function (error) {
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
         * User Mutations
         **********************/
        SET_USER: (state, { user }) => {
            state.user = user;
        },
        /***********************
         * Project Mutations
         **********************/
        SET_PROJECT_LIST: (state, { projects }) => {
            projects.forEach(function(project){
                state.projects.push( new Project(project));
            });
        },
        ADD_PROJECT_SUCCESS: (state, { project }) => {
            /** add new project to data array*/
            state.projects.push(project);
        },
        ADD_PROJECT_FAILURE: (state, { errors }) => {
            /** add form errors */
            state.formErrors = errors;
        },
        /***********************
         * Section Mutations
         **********************/
        ADD_SECTION_SUCCESS: (state, { projectId, section }) => {
            /** cast id to int **/
            let id = parseInt(projectId);
            /** get project index **/
            let idx = state.projects.map(project => project.id).indexOf(id);
            /** add new project to data array*/
            state.projects[idx].sections.push( new Section(section) );
        },
        ADD_SECTION_FAILURE: (state, { errors }) => {
            /** add form errors */
            state.formErrors = errors;
        },
        /***********************
         * Section Mutations
         **********************/
        ADD_TASK_SUCCESS: (state, { projectId, sectionId, task }) => {
            /** cast id to int **/
            let pId = parseInt(projectId);
            let sId = parseInt(sectionId);
            /** get project index **/
            let pIdx = state.projects.map(project => project.id).indexOf(pId);
            let sIdx = state.projects[pIdx].sections.map(section => section.id).indexOf(sId);
            /** add new task to data array **/
            state.projects[pIdx].sections[sIdx].tasks.push( new Task(task) );
        },
        ADD_TASK_FAILURE: (state, { errors }) => {
            /** add form errors */
            state.formErrors = errors;
        },
        UPDATE_TASK_SUCCESS: (state, { projectId, sectionId, id, task }) => {
            /** cast id to int **/
            let pId = parseInt(projectId);
            let sId = parseInt(sectionId);
            let tId = parseInt(id);
            /** get project index **/
            let pIdx = state.projects.map(project => project.id).indexOf(pId);
            let sIdx = state.projects[pIdx].sections.map(section => section.id).indexOf(sId);
            let tIdx = state.projects[pIdx].sections[sIdx].tasks.map(tasks => task.id).indexOf(tId);
            /** update task to data array **/
            state.projects[pIdx].sections[sIdx].tasks[tIdx] = new Task(task);
        },
        UPDATE_TASK_FAILURE: (state, { errors }) => {
            /** add form errors */
            state.formErrors = errors;
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
        },
        SET_BUTTON_TO_LOADING: (state,{name}) =>{
            let idx = state.modals.map(modal => modal.name).indexOf(name);
            state.modals[idx].isLoading = true;
        },
        REMOVE_BUTTON_LOADING_STATE: (state,{name}) =>{
            let idx = state.modals.map(modal => modal.name).indexOf(name);
            state.modals[idx].isLoading = false;
        }
    },
    getters: {
        openProjects: state => {
            // return state.projects.filter(project => !project.completed)
        },
        getErrorByName: (state, getters) => (name) => {
            // return state.errors.find(error => error.name === name)
        },
        /***********************
         * Project Getters
         **********************/
        getProjectById: (state, getters) => (projectId) => {
            /** cast id to int **/
            let id = parseInt(projectId);
            return state.projects.find(project => project.id === id)
        },
        /***********************
         * Section Getters
         **********************/
        getSectionById: (state, getters) => ({projectId, sectionId}) => {
            /** cast ids to int **/
            let pId = parseInt(projectId);
            let sId = parseInt(sectionId);
            /** find object index of project **/
            let idx = state.projects.map(project => project.id).indexOf(pId);
            /** find and return section **/
            return state.projects[idx].sections.find(section => section.id === sId);
        },
        /***********************
         * Task Getters
         **********************/
        getTaskByIds: (state, getters) => ({projectId, sectionId, id}) => {
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
        getTask: state => (id) =>{
            /** todo: Refactor this with load dash and git working**/
            let task =  state.projects.forEach(function (project) {
                 project.sections.forEach(function (section) {
                    return section.tasks.find(task => task.id === id);
                });
            });
        },
        getWorkingOnIt: state => {
           let workingOnIt = [];
            /** todo: Refactor this with load dash **/
            state.projects.forEach(function (project) {
               project.sections.forEach(function (section) {
                   section.tasks.forEach(function(task){
                        if(task.status_id === 2){workingOnIt.push(task);}
                   });
               });
           });
            return workingOnIt;
        },
        getOverDue: state => {
           let overDue = [];
           let now = moment();
            /** todo: Refactor this with load dash **/
            state.projects.forEach(function (project) {
                project.sections.forEach(function (section) {
                    section.tasks.forEach(function(task){
                       if( moment(task.due_date).isBefore( now ) ){
                           overDue.push(task);
                       }
                    });
                });
            });
            return overDue;
        },
        getUpComing: state => {
           let upComing = [];
           let now = moment();
           let nextWeek = moment().add(7, 'days');
            /** todo: Refactor this with load dash **/
            state.projects.forEach(function (project) {
                project.sections.forEach(function (section) {
                    section.tasks.forEach(function(task){
                        console.log(now);
                        console.log(nextWeek);
                        console.log(task.due_date);
                        console.log(moment(task.due_date).isBetween(now, nextWeek));
                        if(moment(task.due_date).isBetween(now, nextWeek) ){
                               upComing.push(task);
                           }
                    });
                });
            });
            return upComing;
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