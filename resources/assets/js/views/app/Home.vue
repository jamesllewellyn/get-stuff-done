<template>
    <div>
    <h1 class="title has-text-centered">Stuff That Needs Doing </h1>

    <div class="tabs is-centered">
        <ul>
            <li class="is-active"><a>List</a></li>
            <li><a>Calender</a></li>
            <li><a>Chats</a></li>
        </ul>
    </div>

    <div class="tabs is-toggle is-marginless">
        <ul>
            <li class="is-active">
                <a>
                    <span>Today </span>
                    <span class="tag">20</span>
                </a>
            </li>
            <li>
                <a class="is-danger">
                    <span>Stuff Not Finished</span>
                    <span class="tag is-danger">3</span>
                </a>
            </li>
            <li>
                <a>
                    <span>New Stuff</span>
                    <span class="tag is-success">1</span>
                </a>
            </li>
        </ul>
    </div>
    <div>
        <div class="box">
            <h3><i class="fa fa-caret-down caret" aria-hidden="true"></i>To Do First <span class="tag ">5</span></h3>
            <table class="table task-table">
                <tbody>
                <tr>
                    <td>
                        <i class="fa fa-sort" aria-hidden="true"></i>
                        <a href="#"><i class="fa fa-check-circle-o" aria-hidden="true"></i> </a>
                    </td>
                    <td>Get out of bed</td>
                    <td><a href="#">#Morning</a></td>
                </tr>
                <tr>
                    <td>
                        <i class="fa fa-sort" aria-hidden="true"></i>
                        <a href="#"><i class="fa fa-check-circle-o" aria-hidden="true"></i> </a>
                    </td>
                    <td >Have a shower</td>
                    <td><a href="#">#Morning</a></td>
                </tr>
                <tr>
                    <td>
                        <i class="fa fa-sort" aria-hidden="true"></i>
                        <a href="#"><i class="fa fa-check-circle-o" aria-hidden="true"></i>
                        </a>
                    </td>
                    <td ><p>Get Dressed</p></td>
                    <td><a href="#">#Morning</a></td>
                </tr>
                <tr>
                    <td>
                        <i class="fa fa-sort" aria-hidden="true"></i>
                        <a href="#"><i class="fa fa-check-circle-o" aria-hidden="true"></i> </a>
                    </td>
                    <td >Eat Breakfast</td>
                    <td><a href="#">#Morning</a></td>
                </tr>
                <tr>
                    <td>
                        <i class="fa fa-sort" aria-hidden="true"></i>
                        <a href="#"><i class="fa fa-check-circle-o" aria-hidden="true"></i> </a>
                    </td>
                    <td >Brush Teeth</td>
                    <td><a href="#">#Morning</a></td>
                </tr>
                </tbody>
            </table>
            <h3><i class="fa fa-caret-right caret" aria-hidden="true"></i>To Do Later <span class="tag ">8</span></h3>
            <h3><i class="fa fa-caret-right caret" aria-hidden="true"></i>To Do If I Have Time <span class="tag ">2</span></h3>
            <h3><i class="fa fa-caret-right caret" aria-hidden="true"></i>Moved To Tomorrow <span class="tag ">0</span></h3>
            <h3><i class="fa fa-caret-right caret" aria-hidden="true"></i>Done <span class="tag ">2</span></h3>

        </div>
    </div>
    </div>
</template>

<script>
    import appstore from '../../app-store';

    export default {
        data() {
            return{
                user: appstore.user
            }
        },
        components:{},
        computed: {

        },
        methods: {

        },
        mounted() {
        }
    }
</script>
