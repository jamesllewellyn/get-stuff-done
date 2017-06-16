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
   addTask: function (projectId, sectionId, task) {
      console.log(task);
       /** find project */
        let project =  this.projects.filter(function (project) {
            return project.id == projectId;
        });
              console.log(project);
        /** find section and add task to it */
        project[0].sections.filter(function (section) {
            if(section.id == sectionId){
                section.tasks.push(new Task(task));
            }
        });

    }
}
export default appstore