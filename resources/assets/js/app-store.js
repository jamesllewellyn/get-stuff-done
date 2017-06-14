import Project from './core/Project';
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
    }
}
export default appstore