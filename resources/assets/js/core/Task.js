class Task{
    constructor($data){
        this.id = $data.id;
        this.section_id = $data.section_id;
        this.name = $data.name;
        this.due_date = $data.due_date;
        this.due_time = $data.due_time;
        this.created_at = $data.created_at;
    }
}
export default Task;
