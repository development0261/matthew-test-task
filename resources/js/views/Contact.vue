<template>
    <div>
        <Header></Header>
        <div class="container">
            <div class="row">
                <div class="col-12 mt-3">
                    <div class="w-100 text-right">
                    <b-button v-b-modal.modal-1 >Create Contact</b-button>
                   </div>
                  <table class="table mt-2">
                          <thead>
                            <tr>
                              <th scope="col">#</th>
                              <th scope="col">Name</th>
                              <th scope="col">Email</th>
                              <th scope="col">Address</th>
                              <th scope="col">Created</th>
                              <th colspan="2">Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-if="getcontacts.length > 0" v-for="(contact,index) in getcontacts">
                                <td>{{++index}}</td>
                                <td>{{contact.name}}</td>
                                <td>{{contact.email}}</td>
                                <td>{{contact.address}}</td>
                                <td>{{contact.created_at}}</td>
                                <td class="d-flex"><b-button @click="editData(contact.id)" variant="outline-warning" class="btn btn-sm"> <i class="fas fa-edit"> </i></b-button>
                                <b-button @click="onDelete(contact.id)" variant="outline-danger" class="btn btn-sm ml-2"><i class="fa fa-trash" aria-hidden="true"></i></b-button></td>
                            </tr>
                            <tr v-if="!getcontacts.length">
                                <td colspan="6" class="text-center">No record found</td>
                            </tr>
                          </tbody>
                    </table>
                </div>
            </div>
             <b-modal no-close-on-esc ref="modal-1" id="modal-1" :title="contactFormName" @ok="onClickModalRepair" @hidden="resetModal" :ok-variant="btnSubmitVariant" :ok-title = "btnSubmitTitle">
                <div>
                    <form>
                      <div class="form-group" >
                        <label class="form__label">Name</label>
                        <input class="form-control" type="text"  v-model="contact.name" :class="{ 'is-invalid': submitted && $v.contact.name.$error  }"/>
                        <div v-if="submitted && !$v.contact.name.required" class="invalid-feedback">Name is required</div>
                      </div>
                   <!--    <div class="error" v-if="!$v.contact.name.required">Field is required.</div> -->
                     <!--  <div class="error" v-if="!$v.form.userName.minLength">Field must have at least {{ $v.form.userName.$params.minLength.min }} characters.</div> -->
                      <div class="form-group">
                        <label class="form__input">Email</label>
                        <input class="form-control" v-model="contact.email" type="email" :class="{ 'is-invalid': submitted && $v.contact.email.$error  }"/>
                      <div class="invalid-feedback" v-if="submitted && !$v.contact.email.required">Email is required.</div>
                      <div class="invalid-feedback" v-if="submitted && !$v.contact.email.email">Please enter valid email.</div>
                      </div>
                        <div class="form-group">
                        <label class="form__input">Adresss</label>
                        <input class="form-control" v-model="contact.address" type="text" :class="{ 'is-invalid': submitted && $v.contact.address.$error  }"/>
                       <div class="invalid-feedback" v-if="!$v.contact.address.required">Address is required.</div>
                       <div class="invalid-feedback" v-if="!$v.contact.address.minLength">Address must have at least {{ $v.contact.address.$params.minLength.min }} characters.</div>
                      </div>                  
                  </form>
                    </div>
              </b-modal>
        </div>
    </div>
</template>

<script>
    import Header from './../components/Header'
    import { required, minLength, between,email} from 'vuelidate/lib/validators'
    export default {
     name: "Contact",
        components:{
            Header,
        },
          data() {
            return {
                contactFormName :'Create Contact',
                btnSubmitTitle: 'Submit',
                btnSubmitVariant: 'primary',
                contact_id: null,
                contact:{
                    name:'',
                    email:'',
                    address:''
                },
                submitted:false,
                showLoader: false,
                getcontacts:[],
            }
        },
         validations: {
             contact: {
                name: {
                  required,
                },
                email: {
                  email,
                  required,
                },
                 address:{
                    required,
                    minLength: minLength(4)
                }
             }
          },
        methods:{
            init(){
                 axios.get('/api/contacts')
                    .then((prod) => {
                        this.getcontacts = prod.data.response;
                        this.showLoader=false;
                    }).catch(()=>{
                    this.showLoader=false;
                    this.$toasted.global.error_messsage({message:'Something went wrong'});
                });
            },
            editData(id) {
              axios.get('/api/contacts/edit/'+id)
                    .then((resp) => {                      
                        this.showLoader = false;                        
                        if(resp.data.status=='success'){
                          var c = resp.data.contact;
                          this.contact.name = c.name;
                          this.contact.address = c.address;
                          this.contact.email = c.email;
                          this.contact_id = c.id;
                          this.btnSubmitTitle = 'Update';
                          this.btnSubmitVariant= 'warning';
                          this.contactFormName = 'Edit Contact';
                          this.$refs['modal-1'].show();
                        }else{
                            Vue.$toast.open({
                                message: 'Something went wrong!',
                                type: 'error',
                                // all of other options may go here
                            });
                        }
                    }).catch((e)=>{
                     Vue.$toast.open({
                      message: 'Something went wrong!',
                      type: 'error',
                    // all of other options may go here
                    });                    
                });
            },
            onClickModalRepair(bvModalEvt) {
              this.submitted = true;
              this.$v.$touch();
              if (this.$v.$invalid) {        
                bvModalEvt.preventDefault();
                return;
              }else{
                  var formData = this.contact;
                  if (this.contact_id) {
                    this.contact.contact_id = this.contact_id;
                  }
                  axios.post('/api/contacts', formData)
                    .then((resp) => {
                        this.showLoader = false;
                        if(resp.data.status=='success'){
                          Vue.$toast.open({
                              message: resp.data.message,
                              type: 'success',
                          });
                          this.init();
                        }else{
                          Vue.$toast.open({
                              message: 'Something went wrong!',
                              type: 'error',                                  
                          });
                        }
                    }).catch((e)=>{
                      Vue.$toast.open({
                        message: 'Something went wrong!',
                        type: 'error',                  
                      });
                  });
              }
            },
            resetModal(){
              this.contact = {
                name:'',
                email:'',
                address:'',
              }
              this.contact_id = null;
              this.btnSubmitTitle = 'Submit';
              this.btnSubmitVariant= 'primary';
              this.contactFormName = 'Create Contact';
              this.$v.$reset();
            },
            onDelete(id) {
              this.$bvModal.msgBoxConfirm('Are you sure you want to delete this record?', {
                title: 'Please Confirm',
                size: 'sm',
                buttonSize: 'md',
                okVariant: 'danger',
                okTitle: 'Yes',
                cancelTitle: 'No',
                footerClass: 'p-2',
                hideHeaderClose: false,
                centered: true
              })
                .then(value => {                  
                  if (value==true) {
                    axios.delete('/api/contacts/delete/'+id)
                    .then((resp) => {
                      if (resp.data.status=='success') {
                        this.getcontacts = this.getcontacts.filter(function( contact ) {
                          return contact.id !== id;
                        });
                        Vue.$toast.open({
                          message: resp.data.message,
                          type: 'success',
                        });
                      }else{
                        Vue.$toast.open({
                          message: resp.data.message,
                          type: 'error',
                        });
                      }
                    })
                    .catch((e)=>{                      
                       Vue.$toast.open({
                        message: 'Something went wrong!',
                        type: 'error',                    
                      });
                    });
                  }
                })
                .catch(err => {
                  // An error occurred
                })
            }
        },
        mounted() {
            this.init();
        }
    }
</script>
