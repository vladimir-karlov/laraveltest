<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Users List</h3>

                <div class="card-tools">
                    <button class="btn btn-success" data-toggle="modal" data-target="#addNew" @click="openModalWindow">Add New <i class="fas fa-user-plus fa-fw"></i></button>
                </div>
              </div>
             
              <div class="card-body table-responsive p-0">
                <table class="table table-hover users-table">
                  <tbody>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Created At</th>                        
                  </tr>

                  <tr v-for="user in users" v-bind:key="user.id">
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                    <td>{{ user.created_at | formatDate}}</td>

                    <td>
                        <a href="#" data-id="user.id" data-target="#addNew" data-toggle="modal" @click="editModalWindow(user)">
                            <i class="fa fa-edit blue"></i>
                            Edit
                        </a>

                        |
                        <a href="#" @click="deleteUser(user.id)">
                            <i class="fa fa-trash red"></i>
                            Remove
                        </a>

                    </td>
                  </tr>
                </tbody>
				<pagination :data="users" @pagination-change-page="getResults"></pagination>
                </table>
              </div>
            
              <div class="card-footer">
                 
              </div>
            </div>
           
          </div>
        </div>

        <div class="modal fade" id="addNew" ref="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                  <div class="modal-header">
  
                      <h5 v-show="!editMode" class="modal-title" id="addNewLabel">Add New User</h5>
                      <h5 v-show="editMode" class="modal-title" id="addNewLabel">Update User</h5>
  
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                      </button>
                  </div>

          <form @submit.prevent="editMode ? updateUser() : createUser()">
          <div class="modal-body">
               <div class="form-group">
                  <input v-model="form.title" type="text" name="title"
                      placeholder="Title"
                      class="form-control" :class="{ 'is-invalid': form.errors.has('title') }">
                  <has-error :form="form" field="title"></has-error>
              </div>
            
              <div class="form-group">
              <textarea v-model="form.description" name="description" placeholder="Description" class="form-control" :class="{ 'is-invalid': form.errors.has('description') }"></textarea>                      

                  <has-error :form="form" field="description"></has-error>
              </div>
            
              <div class="form-group">  
              <select v-model="form.selected_user_id" name="selected_user_id" id="selected_user_id" class="form-control" :class="{ 'is-invalid': form.errors.has('selected_user_id') }" >
                 <option selected disabled value="">Please select one</option>
                 <option v-for="user in form.users" :key="user.id" :value="user.id">{{ user.name }}</option>
              </select>
                <has-error :form="form" field="selected_user_id"></has-error>   
              </div>  
              
              <div class="form-group"> 
                <multiselect v-model="form.selected_tags" :multiple="true" :options="form.tags" :class="{ 'is-invalid': form.errors.has('selected_tags') }" track-by="id" label="name" placeholder="Please select" >
                </multiselect>
              
                <has-error :form="form" field="selected_tags"></has-error>    
              </div>
             
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              <button v-show="editMode" type="submit" class="btn btn-primary">Update</button>
              <button v-show="!editMode" type="submit" class="btn btn-primary">Create</button>
          </div>
          
          </form>

                </div>
            </div>
            </div>
    </div>

</template>

<script>
  import Vue from 'vue'
  import { Form, HasError, AlertError } from 'vform';
  Vue.component(HasError.name, HasError);
  Vue.component(AlertError.name, AlertError);

  import $ from 'jquery';

  import axios from 'axios';

  import vuetify from '../plugins/vuetify';

  //Import Sweetalert2
  import Swal from 'sweetalert2'
  window.Swal = Swal
  const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    onOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
  })
  

//Setup custom events in vue
let Fire = new Vue()

  import VueProgressBar from '../progressbar.js'; 

   //Import Vue Filter
  require('../filter');  

  //Import multiselect
  import Multiselect from 'vue-multiselect';
  
  // register globally
  Vue.component('multiselect', Multiselect)  

    export default { 
        name: 'UserComponent',
        mounted() {
        },
        data() {
            return {
                editMode: false,
                users: [],
                form: new Form({
                    id: '',
                    title : '',
                    description: '',
                  selected_user_id: '',
                  users: [],
                  selected_tags: '',                  
                  tags: []                            
                })
            }
        },
        methods: {                
          updateUser(){
             this.form.put('api/users/'+this.form.id)
                 .then(()=>{
  
                     Toast.fire({
                        icon: 'success',
                        title: 'User updated successfully'
                      })
  
                      Fire.$emit('AfterCreatedUserLoadIt');
  
                      $('#addNew').modal('hide');
                 })
                 .catch(()=>{
                    console.log('Error updating the user')
                 })
          },
          
          openModalWindow(){
             this.editMode = false
             this.form.reset();
             this.populateUserDropdown();
             this.populateTagDropdown();
             console.log($('#addNew'));               
             $('#addNew').modal('show');
          },

          editModalWindow(user){
             this.form.clear();
             this.editMode = true;
             this.form.reset();        
             $('#addNew').modal('show');
             this.form.fill(user);
             this.populateUserDropdown();
             this.populateTagDropdown();               
             
             this.form.selected_user_id = user.user.id;
             this.form.selected_tags = user.tags;
          },          

          loadUsers(){
            axios.get('api/users').then((response) => { 
              this.users = response.data;                             
            });
          },
          
        populateUserDropdown(){
          axios.get('api/users').then((response) => {        
             this.form.users = response.data;

          }); 
        },
        
        populateTagDropdown(){
          axios.get('api/tags').then((response) => {         
             this.form.tags = response.data;

          }); 
        },            

          createUser(){
  
              this.$Progress.start()
  
              this.form.post('api/users')
                  .then(() => {
                      Fire.$emit('AfterCreatedUserLoadIt');
                      Toast.fire({
                        icon: 'success',
                        title: 'User created successfully'
                      });
                      this.$Progress.finish();
                      $('#addNew').modal('hide');
                  })
                  .catch(() => {
                     console.log('Error while creating the user')
                  })
  
              //this.loadUsers();
          },
          
            deleteUser(id) {
              Swal.fire({
                title: 'Are you sure?',
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
              }).then((result) => {
                  
                if (result.value) {
                  //Send Request to serverf
                  this.form.delete('api/users/'+id)
                      .then((response)=> {
                              Swal.fire(
                                'Deleted!',
                                'User deleted successfully',
                                'success'
                              )
                      this.loadUsers();
  
                      }).catch(() => {
                          Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                            footer: '<a href>Why do I have this issue?</a>'
                          })
                      })
                  }
  
              })
            }
          },

          created() { 
              this.loadUsers();            
  
              Fire.$on('AfterCreatedUserLoadIt',()=>{
                  this.loadUsers();
              });
          }
    }
</script> 

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>