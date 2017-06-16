class Task{
    constructor($data){
        this.id = $data.id;
        this.name = $data.name;
        this.done = $data.done;
        this.priority_id = $data.priority_id;
        this.due_date = $data.due_date;
        this.due_time = $data.due_time;
        this.note = $data.note;
        this.created_at = $data.created_at;
    }
    isDone(){
        this.done = true;
    }
    clear(){
        this.id = '';
        this.name = '';
        this.done = '';
        this.priority_id = '';
        this.due_date = '';
        this.due_time = '';
        this.note = '';
        this.created_at = '';
    }
}
export default Task;
