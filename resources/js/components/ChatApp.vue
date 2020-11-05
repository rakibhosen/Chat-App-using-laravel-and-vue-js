<template>
    <div>
      <div class="container-chat  clearfix">
    <div class="people-list" id="people-list">
      <div class="search">
        <input type="text" placeholder="search" />
        <i class="fa fa-search"></i>
      </div>
      <ul class="list">
       
        <li @click.prevent="selectUser(user.id)" class="clearfix" v-for="(user,index) in userList" :key="user.id">
          <img :src="`uploadImage/user.png`" alt="avatar" height="40" width="40"/>
          <div class="about">
            <div class="name">{{ user.name }}</div>
            <div class="status">
              <i class="fa fa-circle online"></i> online
            </div>
  
          </div>
        </li>

      </ul>
    </div>
    
    <div class="chat">
      <div class="chat-header clearfix">
        <img :src="`uploadImage/user.png`" alt="avatar" height="40" width="40"/>
        
        <div class="chat-about">
          <div class="chat-with" v-if="userMessage.user">{{userMessage.user.name}}</div>
          
        </div>
                                          <ul class="nav nav-tabs">
                <li class="nav-item dropdown">
                  <a class="nav-item dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">...</a>
                   <div class="dropdown-menu">
                     <a @click.prevent="deleteMultipleMessage" class="dropdown-item" href="">Delete all message</a>
                   </div>
                </li>
              </ul>
        <i class="fa fa-star"></i>
      </div>
      
      <div class="chat-history" v-chat-scroll>
        <ul>

          <li class="clearfix" v-for="message in userMessage.messages" :key="message.id">
            <div class="message-data align-right">
              <span class="message-data-time" >{{ message.created_at|timeformat }}</span> &nbsp; &nbsp;
              <span class="message-data-name" >{{ message.user.name }}</span> <i class="fa fa-circle me"></i>
              <ul class="nav nav-tabs">
                <li class="nav-item dropdown">
                  <a class="nav-item dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">...</a>
                   <div class="dropdown-menu">
                     <a @click.prevent="deleteSingleMessage(message.id)" class="dropdown-item" href="">Delete</a>
                   </div>
                </li>
              </ul>
            </div>
            <div :class="`message ${message.user.id==userMessage.user.id?'other-message float-right':'my-message'}`">
             {{ message.message }}
            </div>
          </li>
          
          <!-- <li>
            <div class="message-data">
              <span class="message-data-name"><i class="fa fa-circle online"></i> Vincent</span>
              <span class="message-data-time">10:20 AM, Today</span>
            </div>
            <div class="message my-message">
              Actually everything was fine. I'm very excited to show this to our team.
            </div>
          </li> -->
          
          <li  v-if="typing">
            <!-- <div class="message-data">
              <span class="message-data-name"><i class="fa fa-circle online"></i>{{typing}}</span>
              
            </div> -->
            <i class="fa fa-circle online"></i>
            <i class="fa fa-circle online" style="color: #AED2A6"></i>
            <i class="fa fa-circle online" style="color:#DAE9DA"></i>
          </li>
          
        </ul>
        
      </div> <!-- end chat-history -->
      
      <div class="chat-message clearfix">
         <!-- <p v-if="typing">{{typing}} typing....</p> -->
         <textarea v-if="userMessage.user" @keydown.enter="sendMessage" @keydown="typeingEvent(userMessage.user.id)" v-model="message" name="message-to-send" id="message-to-send" placeholder ="Type your message" rows="3"></textarea>
        <textarea v-else disabled id="message-to-send" placeholder ="Type your message" rows="3"></textarea>
                
        <i class="fa fa-file-o"></i> &nbsp;&nbsp;&nbsp;
        <i class="fa fa-file-image-o"></i>
        
        <button>Send</button>

      </div> <!-- end chat-message -->
      
    </div> <!-- end chat -->
    
  </div> <!-- end container -->

<!-- <script id="message-template" type="text/x-handlebars-template">
  <li class="clearfix">
    <div class="message-data align-right">
      <span class="message-data-time" >{{time}}, Today</span> &nbsp; &nbsp;
      <span class="message-data-name" >Olia</span> <i class="fa fa-circle me"></i>
    </div>
    <div class="message other-message float-right">
      {{messageOutput}}
    </div>
  </li>
</script>

<script id="message-response-template" type="text/x-handlebars-template">
  <li>
    <div class="message-data">
      <span class="message-data-name"><i class="fa fa-circle online"></i> Vincent</span>
      <span class="message-data-time">{{time}}, Today</span>
    </div>
    <div class="message my-message">
      {{response}}
    </div>
  </li>
</script> -->

    </div>
</template>

<script>
    export default {
      data(){
      return {
        message:'',
        typing:''
      }
      },
      
      mounted(){
    Echo.private(`chat.${authUser.id}`)
    .listen('MessageSend', (e) => {
       this.selectUser(e.messages.from);
      // // console.log('hi');
      //   console.log(e.messages.from);
    });
       this.$store.dispatch('userList')

Echo.private('typingevent')
    .listenForWhisper('typing', (e) => {
      if(e.user.id==this.userMessage.user.id && e.userId == authUser.id){
        this.typing = e.user.name;
      }
        setTimeout(() => {
          this.typing = '';
        }, 2000);
    });





      },
      created(){

      },
      computed:{
       userList(){
        return  this.$store.getters.userList;
       },

      userMessage(){
        return  this.$store.getters.userMessage;
       },

      },
      methods:{
         selectUser(userId){
           this.$store.dispatch('userMessage',userId);
         },

         sendMessage(e){
           console.log(this.userMessage.user.id)
           e.preventDefault();
           if(this.message!=''){
              axios.post('/sendmessage',{message:this.message, user_id:this.userMessage.user.id})
              .then(response=>{
              this.selectUser(this.userMessage.user.id)
              })
              this.message = '';
           }
         },
         deleteSingleMessage(id){
             axios.get('/deletesinglemsg/'+id)
              .then(response=>{
             
             this.selectUser(this.userMessage.user.id)
              })
         },
             deleteMultipleMessage(){
             axios.get(`/deletemultiplemsg/${this.userMessage.user.id}`)
              .then(response=>{
             console.log(response.data.message)
             this.selectUser(this.userMessage.user.id)
              })
         },

    typeingEvent(userId){
      Echo.private('typingevent')
      .whisper('typing', {
          'user': authUser,
          'typing':this.message,
          'userId':userId
      });
    },
         
      }
    }
</script>
<style scoped>
.people-list ul {
    height: 500px!important;
    overflow-y: scroll!important;
}
</style>
