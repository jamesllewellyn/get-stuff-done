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
    update(task){
        this.name = task.name;
        this.status_id = task.status_id;
        this.priority_id = task.priority_id;
        this.due_date = task.due_date;
        this.due_time = task.due_time;
        this.note = task.note;
        this.sort_order = task.sort_order;
        return true;
    }

}
export default Task;
