import {defineStore} from "pinia";
import {Axios} from "axios";


export let useChats = defineStore("chats", {
    state:()=>({
        count:0,
        users:[],
        user: null,
    }),

    actions:{
        increments(){
            this.count++;
        },
        async setAllUsers(value = null, is_student=false){
            await axios.get(`/chat/users?search=${value}&is_student=${is_student}`).then(res => this.users = res.data).catch((err)=> console.log(err));
        },
        async setUserById(id = null){
            await axios.get(`/chat/users/${id}`).then(res => this.user = res.data).catch((err)=> console.log(err));
        }
    },

    getters:{
        getRemaning(){
            return 10 - this.count;
        },
        getAllUsers(){
            return this.users;
        },
        getUserById(){
            return this.user;
        }
    }

})
