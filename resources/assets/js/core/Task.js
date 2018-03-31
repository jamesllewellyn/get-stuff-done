class Task{
    constructor(data){
        if(! data){
            return this.setDefaultValues();
        }
        this.id = data.id;
        this.name = data.name;
        this.status_id = data.status_id;
        this.priority_id = data.priority_id;
        this.due_date = data.due_date;
        this.sort_order = data.sort_order;
        this.due_time = data.due_time;
        this.section = data.section;
        this.note = data.note;
        this.users = data.assigned_users;
        this.comments = data.comments;
        this.created_at = data.created_at;
        if( typeof data.section.project !== 'undefined'){
            this.project = data.section.project;
        }
    }
    setDefaultValues(){
        this.id = null;
        this.name = null;
        this.status_id = null;
        this.priority_id = null;
        this.due_date = null;
        this.sort_order = null;
        this.due_time = null;
        this.section = null;
        this.note = null;
        this.users = null;
        this.comments = null;
        this.created_at = null;
        this.project = null;
        return true;
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
    getPriority(){
        let priority = null;
        switch (this.priority_id){
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
    setPriority(priority_id){
        return this.priority_id = priority_id;
    }
    getStatus(){
        let status = null;
        switch (this.status_id){
            case 1 :
                status = {id:1, name:'Done'};
                break;
            case 2 :
                status = {id:2, name:'Working On It'};
                break;
        }
        return status;
    }
    setStatus(status_id){
        return this.status_id = status_id;
    }

}
export default Task;
