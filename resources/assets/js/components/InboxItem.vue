<template>
    <div class="box" >
        <article class="media">
            <div class="media-left">
                <figure class="image is-64x64">
                    <img class="circle" :src="inboxItem.data.user.avatar_url" alt="Image" v-if="inboxItem">
                </figure>
            </div>
            <div class="media-content">
                <div class="content">
                    <p>
                        <strong v-text="inboxItem.data.user.full_name"></strong> <small v-text="handle"></small>
                        <br>
                        <span v-text="inboxItem.data.action"></span>
                    </p>
                </div>
                <nav class="level is-mobile">
                    <div class="level-left">
                        <a class="level-item">
                            <span class="icon is-small" @click="view()"><i class="fa fa-reply"></i></span>
                        </a>
                        <!--<a class="level-item">-->
                            <!--<span class="icon is-small" ><i class="fa fa-retweet"></i></span>-->
                        <!--</a>-->
                        <a class="level-item">
                            <span class="icon is-small" @click="markAsRead()"><i class="fa fa-check"></i></span>
                        </a>
                    </div>
                </nav>
            </div>
        </article>
    </div>
</template>

<script>
    import store from '../store';
    export default {
        props: {
            inboxItem : {
                type: Object,
                required: true
            }
        },
        computed: {
            handle: function () {
                return '@'+this.inboxItem.data.user.handle;
            },
        },
        methods: {
            markAsRead:function(){
                store.dispatch('NOTIFICATION_MARK_AS_READ', { id: this.inboxItem.id });
            },
            view: function () {
                let type = this.inboxItem.type.split("\\");
                switch(type['2']){
                    case 'UserAssignedTask':
                    case 'UserRemovedFromTask':
                    case 'UserAssignedTaskCompleted':
                        return store.dispatch('USER_CAN_ACCESS_TASK', {
                            teamId: this.inboxItem.data.team_id,
                            projectId: this.inboxItem.data.project_id,
                            sectionId: this.inboxItem.data.section_id,
                            taskId : this.inboxItem.data.task_id }
                        );
                    case 'ProjectAdded':
                        return store.dispatch('USER_CAN_ACCESS_PROJECT', { teamId: this.inboxItem.data.team_id,  projectId: this.inboxItem.data.project_id });
                    case 'AddedToTeam':
                        return store.dispatch('USER_CAN_ACCESS_TEAM', { teamId: this.inboxItem.data.team_id});
                }
            },
        },
        mounted() {
        }
    }
</script>
