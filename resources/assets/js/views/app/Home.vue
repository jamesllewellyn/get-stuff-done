<template>
    <div class="container">
        <h1 class="title">
            Dashboard
        </h1>
        <div class="has-text-right">
            <span class="tag is-orange is-medium">
                <a  @click.prevent.stop="toggleModal('addProject')" class="orange">Add Project</a>
            </span>
        </div>
        <!--<div class="tabs is-centered">-->
            <!--<ul>-->
                <!--<li class="is-active"><a>List</a></li>-->
                <!--<li><a>Calender</a></li>-->
                <!--<li><a>Chats</a></li>-->
            <!--</ul>-->
        <!--</div>-->
        <hr />
        <div>
            <div class="columns is-multiline">
                <div class=" column is-full">
                    <div class="box">
                        <h3 class="has-text-centered">Working On It</h3>
                        <table class="table task-table">
                            <draggable v-model="workingOnIt" @start="drag=true" :options="{handle:'.handle'}"  @end="drag=false"  :element="'tbody'">
                                <dashboardTask v-for="task in workingOnIt"  :task="task"  :key="task.id"></dashboardTask>
                            </draggable>
                        </table>
                    </div>
                </div>
                <div class=" column is-half">
                    <div class="box">
                        <h3 class="has-text-centered">Over Due</h3>
                        <table class="table task-table">
                            <draggable v-model="workingOnIt" @start="drag=true" :options="{handle:'.handle'}"  @end="drag=false"  :element="'tbody'">
                                <dashboardTask v-for="task in overDue"  :task="task"  :key="task.id"></dashboardTask>
                            </draggable>
                        </table>
                    </div>
                </div>
                <div class=" column is-half">
                    <div class="box">
                        <h3 class="has-text-centered">Deadlines Coming</h3>
                        <table class="table task-table">
                            <draggable v-model="workingOnIt" @start="drag=true" :options="{handle:'.handle'}"  @end="drag=false"  :element="'tbody'">
                                <dashboardTask v-for="task in upComing"  :task="task"  :key="task.id"></dashboardTask>
                            </draggable>
                        </table>
                    </div>
                </div>
            </div>
        </div>


    <!--<div class="tabs is-toggle is-marginless">-->
        <!--<ul>-->
            <!--<li class="is-active">-->
                <!--<a>-->
                    <!--<span>Today </span>-->
                    <!--<span class="tag">20</span>-->
                <!--</a>-->
            <!--</li>-->
            <!--<li>-->
                <!--<a class="is-danger">-->
                    <!--<span>Stuff Not Finished</span>-->
                    <!--<span class="tag is-danger">3</span>-->
                <!--</a>-->
            <!--</li>-->
            <!--<li>-->
                <!--<a>-->
                    <!--<span>New Stuff</span>-->
                    <!--<span class="tag is-success">1</span>-->
                <!--</a>-->
            <!--</li>-->
        <!--</ul>-->
    <!--</div>-->
    <!--<div>-->
        <!--<div class="box">-->
            <!--<h3><i class="fa fa-caret-down caret" aria-hidden="true"></i>To Do First <span class="tag ">5</span></h3>-->
            <!--<table class="table task-table">-->
                <!--<tbody>-->
                <!--<tr>-->
                    <!--<td>-->
                        <!--<i class="fa fa-sort" aria-hidden="true"></i>-->
                        <!--<a href="#"><i class="fa fa-check-circle-o" aria-hidden="true"></i> </a>-->
                    <!--</td>-->
                    <!--<td>Get out of bed</td>-->
                    <!--<td><a href="#">#Morning</a></td>-->
                <!--</tr>-->
                <!--<tr>-->
                    <!--<td>-->
                        <!--<i class="fa fa-sort" aria-hidden="true"></i>-->
                        <!--<a href="#"><i class="fa fa-check-circle-o" aria-hidden="true"></i> </a>-->
                    <!--</td>-->
                    <!--<td >Have a shower</td>-->
                    <!--<td><a href="#">#Morning</a></td>-->
                <!--</tr>-->
                <!--<tr>-->
                    <!--<td>-->
                        <!--<i class="fa fa-sort" aria-hidden="true"></i>-->
                        <!--<a href="#"><i class="fa fa-check-circle-o" aria-hidden="true"></i>-->
                        <!--</a>-->
                    <!--</td>-->
                    <!--<td ><p>Get Dressed</p></td>-->
                    <!--<td><a href="#">#Morning</a></td>-->
                <!--</tr>-->
                <!--<tr>-->
                    <!--<td>-->
                        <!--<i class="fa fa-sort" aria-hidden="true"></i>-->
                        <!--<a href="#"><i class="fa fa-check-circle-o" aria-hidden="true"></i> </a>-->
                    <!--</td>-->
                    <!--<td >Eat Breakfast</td>-->
                    <!--<td><a href="#">#Morning</a></td>-->
                <!--</tr>-->
                <!--<tr>-->
                    <!--<td>-->
                        <!--<i class="fa fa-sort" aria-hidden="true"></i>-->
                        <!--<a href="#"><i class="fa fa-check-circle-o" aria-hidden="true"></i> </a>-->
                    <!--</td>-->
                    <!--<td >Brush Teeth</td>-->
                    <!--<td><a href="#">#Morning</a></td>-->
                <!--</tr>-->
                <!--</tbody>-->
            <!--</table>-->
            <!--<h3><i class="fa fa-caret-right caret" aria-hidden="true"></i>To Do Later <span class="tag ">8</span></h3>-->
            <!--<h3><i class="fa fa-caret-right caret" aria-hidden="true"></i>To Do If I Have Time <span class="tag ">2</span></h3>-->
            <!--<h3><i class="fa fa-caret-right caret" aria-hidden="true"></i>Moved To Tomorrow <span class="tag ">0</span></h3>-->
            <!--<h3><i class="fa fa-caret-right caret" aria-hidden="true"></i>Done <span class="tag ">2</span></h3>-->

        <!--</div>-->
    <!--</div>-->
    </div>
</template>

<script>
    import appstore from '../../app-store';
    import draggable from 'vuedraggable'
    import dashboardTask from '../../components/DashboardTask.vue';
    export default {
        data() {
            return{
                user: appstore.user,
                projects: appstore.projects
            }
        },
        components: {
            dashboardTask , draggable
        },
        computed: {
            /**
             * todo: Refactor all of theses methods to remove loops in loops
             * **/
            workingOnIt: function(){
                let workingOn = [];
                let projectId = '';
                let sectionId = '';
                /** todo: this is bad :( refactor ASAP **/
                this.projects.forEach(function (project) {
                    projectId = project.id;
                    project.sections.forEach( function(section){
                        sectionId = section.id;
                        section.tasks.forEach(function (task) {
                            if(task.status_id == 2){
                                task.projectId = projectId;
                                task.sectionId = sectionId;
                                workingOn.push(task);
                            }
                        });
                    });
                });
                return workingOn;
            },
            overDue: function(){
                let overDue = [];
                let now = moment();
                /** todo: this is bad :( refactor ASAP **/
                this.projects.forEach(function (project) {
                    project.sections.forEach( function(section){
                        section.tasks.forEach(function (task) {
                            if( moment(task.due_date).isBefore( now ) ){
                                overDue.push(task);
                            }
                        });
                    });
                });
                return overDue;
            },
            upComing: function(){
                let upComing = [];
                let now = moment();
                let newWeek = moment().days(7);
                /** todo: this is bad :( refactor ASAP **/
                this.projects.forEach(function (project) {
                    project.sections.forEach( function(section){
                        section.tasks.forEach(function (task) {
                            if(moment(task.due_date).isBetween(now, newWeek) ){
                                upComing.push(task);
                            }
                        });
                    });
                });
                return upComing;
            }
        },
        methods: {
            /** trigger toggle modal event */
            toggleModal: function(modalName){
                Event.$emit(modalName);
            }
        },
        mounted() {
        }
    }
</script>
