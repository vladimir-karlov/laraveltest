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

                  <tr v-for="user in users.data" :key="user.id">
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>                    
                    <td>{{ user.created_at | formatDate}}</td>

                    <td>
                        <a href="#" data-id="user.id" @click="editModalWindow(user)">
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

                
                </table>
				<pagination align="center" :data="users" @pagination-change-page="loadUsers"></pagination>                                
              </div>
            
              <div class="card-footer">
                 
              </div>
            </div>
           
          </div>
        </div>

            <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
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
					        <input v-model="form.name" type="text" name="name"
					            placeholder="Name"
					            class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
					        <has-error :form="form" field="name"></has-error>
					    </div>
					    
					     <div class="form-group">
					        <input v-model="form.email" type="text" name="email"
					            placeholder="Email"
					            class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
					        <has-error :form="form" field="email"></has-error>
					    </div>	
					    
					     <div v-show="!editMode" class="form-group">
					        <input v-model="form.password" type="password" name="password"
					            placeholder="Password"
					            class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
					        <has-error :form="form" field="password"></has-error>
					    </div>		
					    
					     <div v-show="!editMode" class="form-group">
					        <input v-model="form.password_confirmation" type="password" name="password_confirmation"
					            placeholder="Confirm Password"
					            class="form-control" :class="{ 'is-invalid': form.errors.has('password_confirmation') }">
					        <has-error :form="form" field="password_confirmation"></has-error>
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
	import pagination from 'laravel-vue-pagination'
    export default { 
        mounted() {
        },
        components:{
            pagination
        },        
        data() {
            return {
                editMode: false,
                users:{
                    type:Object,
                    default:null
                },
                form: new Form({
                    id: '',
                    name : '',
                    email: '',              
                })
            }
        },
        methods: {   	         	        		    
	        editModalWindow(user){
	           this.form.clear();
	           this.editMode = true;
	           this.form.reset();
	           $('#addNew').modal('show');

	           this.form.fill(user);          
	           
	        },

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
	                  console.log("Error updating the user")
	               })
	        },
	        
	        openModalWindow(){
	           this.editMode = false
	           this.form.reset();		           
	           $('#addNew').modal('show');
	        },

	        loadUsers(page = 1){
	        	axios.get("api/users?page=" + page)
	        	.then(
	        		(response) => { 
		        		this.users = response.data;      					        		
					}
				)
	        	.catch(function (error) {
                    this.errors.push(error);
                    console.log(error);
                })
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
	                        })
	
	                        this.$Progress.finish()
	
	                        $('#addNew').modal('hide');
	
	                })
	                .catch(() => {
	                   console.log("Error while creating the user")
	                })
	
	            //this.loadUsers();
	        },
	        
	          deleteUser(id) {
	            Swal.fire({
	              title: 'Are you sure?',
	              text: "You won't be able to revert this!",
	              icon: 'warning',
	              showCancelButton: true,
	              confirmButtonColor: '#3085d6',
	              cancelButtonColor: '#d33',
	              confirmButtonText: 'Yes, delete it!'
	            }).then((result) => {
	                
	              if (result.value) {
	                //Send Request to server
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