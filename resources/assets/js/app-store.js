import Project from './core/Project';
import Task from './core/Task';

let appstore = {
    user: {
        id : "",
        name : "",
        email : ""
    },
    projects: [],
    initiateUser: function (user) {
        this.user.email = user.email;
        this.user.name = user.name;
        this.user.id  = user.id;
    },
    setProjectData: function (projects) {
        let self = this;
        projects.forEach(function(project) {
            self.projects.push(new Project(project));
        });
    },
    addProject: function (project){
        this.projects.push( new Project(project));
    },
    addSection: function (projectId, section){
        console.log(projectId);
        console.log(section);
       /** find project */
        let project =  this.projects.filter(function (project) {
            return project.id == projectId;
        });
        console.log(project[0]);
        project[0].addSection( section );
    },
   addTask: function (projectId, sectionId, task) {
       /** find project */
        let project =  this.projects.filter(function (project) {
            return project.id == projectId;
        });
        /** find section and add task to it */
        project[0].sections.filter(function (section) {
            if(section.id == sectionId){
                section.addTask(task);
            }
        });
    },
   updateTask: function (projectId, sectionId, updatedTask) {
       /** find project */
        let project =  this.projects.filter(function (project) {
            return project.id == projectId;
        });
        /** find section */
        let section = project[0].sections.filter(function (section) {
            return section.id == sectionId;
        });
        /** find task **/
        section[0].tasks.filter(function (task) {
            if(task.id == updatedTask.id){
                task.update(updatedTask);
            }
        });
    },
    getTask(projectId, sectionId, taskId){
       /** find project */
        let project =  this.projects.filter(function (project) {
            return project.id == projectId;
        });
        /** find section */
        let section = project[0].sections.filter(function (section) {
            return section.id == sectionId;
        });
        /** find task **/
        let task = section[0].tasks.filter(function (task) {
            return task.id == taskId;
        });
        return task[0];
    }
}
export default appstore