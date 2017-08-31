class Notification{
    constructor(data){
        this.id = data.id;
        this.type = data.type;
        this.created_at = moment();
        this.data = {
            action : data.action,
            project_id: this.checkIfKeyAndReturn('project_id', data),
            section_id: this.checkIfKeyAndReturn('section_id', data),
            task_id: this.checkIfKeyAndReturn('task_id', data),
            team_id:this.checkIfKeyAndReturn('team_id', data) ,
            user : data.user
        }
    }

    checkIfKeyAndReturn(field, obj){
        if(field in obj){
            return obj[field];
        }
        return null;
    }
}

export default Notification;
