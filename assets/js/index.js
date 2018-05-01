var gURL = 'http://localhost:80/php-school-project/services.php?q=';
var dataURL = 'viewAllP';
var deleteUrl = 'deleteP&p=';
var searchUrl = 'searchP&p=';
var interestsUrl =  'getUserInterest&p='

var App = new Vue({
    el: '#app',
    data: {
        keyword: '',
        url: '',
        persons: [],
        message: "anime",
        firstName: "",
        lastName: "",
        age: 0,
        phone: 0,
        active: "",
        interests: [],
        fullInterests: [],
        constPersons: []
    },
    methods: {
      removeRow: function (id,index) {
          var xhr = new XMLHttpRequest();
          console.log('removing', id);
          xhr.open('DELETE', gURL + deleteUrl + id, true);
          xhr.send();
          this.persons.splice(index, 1);
      },
      search: function () {
          var self = this;
          $.getJSON(gURL + searchUrl + this.keyword, function (data) {
              self.persons = data;
          });
      },
      getBack: function () {
          this.persons = this.constPersons;
      },

        viewPerson: function (id) {
            this.id = this.persons[id].id;
            this.firstName = this.persons[id].firstName;
            this.lastName = this.persons[id].lastName;
            this.age = this.persons[id].age;
            this.phone = this.persons[id].phone;
            this.active = this.persons[id].active;
            if(this.persons[id].active==1){
                this.active = "Yes";
            }
            else this.active = "No";
            var self = this;
            $.getJSON(gURL + interestsUrl + this.id, function (data) {
                console.log(1 + data);
                self.interests = data;
            });
        }
    },
    mounted() {
        var self = this;
        $.getJSON(gURL + dataURL, function (data) {
            self.persons = data;
            self.constPersons = data;
        });
    }
});

