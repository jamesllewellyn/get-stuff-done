class Task{
    constructor(data){
        this.id = data.id;
        this.name = data.name;
        this.status_id = data.status_id;
        this.priority_id = data.priority_id;
        this.due_date = data.due_date;
        this.sort_order = data.sort_order;
        this.due_time = data.due_time;
        this.note = data.note;
        this.users = data.assigned_users;
        this.created_at = data.created_at;
    }
    isDone(){
        this.status_id = 1;
        return true;
    }
    clear(){
        this.id = '';
        this.name = '';
        this.status_id = '';
        this.priority_id = '';
        this.due_date = '';
        this.due_time = '';
        this.note = '';
        this.created_at = '';
        this.sort_order = '';
        return true;
    }
    priority(priorityId){
        let priority = null;
        switch (priorityId){
            case 1 :
                priority = {id:1, name:'High'};
                break;
            case 2 :
                priority = {id:2, name:'Medium'};
                break;
            case 3 :
                priority = {id:3, name:'Low'};
                break;
                priority = {id:3, name:'Low'};
        }
        return priority;
    }
    status(statusId){
        let status = null;
        switch (statusId){
            case 1 :
                status = {id:1, name:'Done'};
                break;
            case 2 :
                status = {id:2, name:'Working On It'};
                break;
        }
        return status;
    }

}
export default Task;
