import Task from './Task';
class Section{
    constructor($data){
        let self = this;
        this.id = $data.id;
        this.name = $data.name;
        this.created_at = $data.created_at;
        this.tasks = [];
        if($data.tasks){
            $data.tasks.forEach(function(task) {
                self.tasks.push(new Task(task));
            });
        }
    }
    addTask(task){
        /** Add new task to section*/
        this.tasks.push(new Task(task));
        return true;
    }
    // getWorkingOnIt(){
    //     let workingOnit =  this.tasks.filter(function (task) {
    //         if(task.status_id = 2){
    //             return task;
    //         }
    //     });
    //     return workingOnit;
    // }
}
export default Section;
