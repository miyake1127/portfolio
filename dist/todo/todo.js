let todos = new Vue( {

    data:{
        text:"",
        todos:[]
      },

    template:
    '<div>'+
    '<form v-on:submit.prevent>'+
    '<input type="text" v-model="text">'+
    ' <button v-on:click="addItem">Add</button>'+
    '</form>'+
    '<ul>'+
    '<li v-for="todo in todos">'+
    '<input type="checkbox" v-model="todo.canDelete">'+
    '<span v-bind:class="{delete: todo.canDelete}">{{todo.text}}</span>'+
    '</li>'+
    '</ul>'+
    '<button v-on:click="deleteAllItem">delete</button>'+
    '</div>',

    methods:{
        addItem:function(){
            if(this.text=="")return;
            let todo = {
                text:this.text,
                canDelete:false,
              };

            this.todos.push(todo);
            this.text="";
        },
        deleteAllItem:function(){
            this.todos.forEach((todo , index)=> {            
                if(todo.canDelete==true){
                    this.todos.splice(index, 1);
                }
            });
            
        }
    }
  
  } );
  
  // 要素にマウントする
  todos.$mount( '#todo' );