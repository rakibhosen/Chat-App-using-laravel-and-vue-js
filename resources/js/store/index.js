import Axios from "axios"

export default{
    state:{
        userList:[],
        userMessage:[]
    },

    getters:{
        userList(state){
            return state.userList;
        },
        userMessage(state){
            
            console.log('stateuser',state.userMessage)
            return state.userMessage;
          
        }
        
    },


    actions:{
       userList(context){
            axios.get('/userlist')
                .then((response)=>{
                    context.commit('userList',response.data.data)
                })
        },

        userMessage(context,payload){
           
            axios.get('/usermessage/'+payload)
                .then(response=>{
                    console.log(response.data)
                    context.commit('userMessage',response.data)
                })
        },


    },
    

    mutations:{
        userList(state,payload){
          return  state.userList = payload
        },

        userMessage(state,payload){
            return state.userMessage = payload
        }
    
    }
}
