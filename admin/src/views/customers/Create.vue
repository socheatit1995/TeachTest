<template>
    <CRow>
      <CToast :autohide="true" color="success" class="text-white align-items-center toast_custom_style" visible v-if="messageSuccess !== ''">
        <div class="d-flex">
          <CToastBody>{{ messageSuccess }}</CToastBody>
          <CToastClose class="me-2 m-auto" white />
        </div>
      </CToast>
      <CCol :xs="12">
        <CCard class="mb-4">
          <CCardHeader>
            <strong>CUSTOMER</strong>
          </CCardHeader>
          <CCardBody>
                <CForm @submit.prevent="handleSubmit" class="p-4 custom_form">
                  <h1>Create Customer</h1>
                  <CInputGroup class="mb-3">
                    <CFormInput placeholder="Name" v-model="name" autocomplete="name" @input="onchangeInput($event,1)" />
                    <CFormFeedback  v-if="showErrorMessage?.name" class="invalid-feedback">{{  showErrorMessage?.name }}</CFormFeedback>
                  </CInputGroup>
                  <CInputGroup class="mb-3">
                    <CFormInput placeholder="PhoneNumber" v-model="phone_number" autocomplete="PhoneNumber"  @input="onchangeInput($event ,2)"/>
                    <CFormFeedback  v-if="showErrorMessage?.phone_number" class="invalid-feedback">{{ showErrorMessage?.phone_number }}</CFormFeedback>
                  </CInputGroup>
                  <CInputGroup class="mb-3">
                    <CFormInput type="date" placeholder="Register Date" v-model="registration_date"  autocomplete="Register Date"  />
                  </CInputGroup>
                  <CInputGroup class="mb-3">
                    <CFormInput placeholder="Address" v-model="address"  autocomplete="Address"  />
                  </CInputGroup>
                  <CInputGroup class="mb-3">
                    <CFormInput placeholder="Score" v-model="scope"  autocomplete="Score"  />
                  </CInputGroup>
                  <div class="text-rigth">
                    <CButton type="submit" color="primary" shape="rounded-pill" :disabled="btnDisabled"> Save </CButton>
                  </div>
                </CForm>
                <div v-if="loading">Loading...</div>
                <div v-if="error">Error: {{ error.message }}</div>

              </CCardBody>
            </CCard>
          </CCol>
        </CRow>
  </template>
  <script>
  import { ref } from 'vue'
  import { useMutation } from '@vue/apollo-composable'
  import gql from 'graphql-tag'
  import router from '@/router'

  export default {
    setup () {
      const name = ref('')
      const registration_date = ref('')
      const phone_number = ref('')
      const address = ref('')
      const score = ref(0)
      const messageSuccess = ref('')
      const showErrorMessage = ref({});
      const btnDisabled = ref(true);

      const formatDate = (dateString) => {
        return dateString.replace(/(\d{2})\/(\d{2})\/(\d{4})/, '$3-$2-$1');
      }

      const createCustomerMutation = gql`
        mutation createCustomer (
          $name: String!
          $phone_number: String!
          $registration_date: Date
          $address: String
          $score: Int
        ) {
            createCustomer (
            name: $name
            phone_number: $phone_number
            registration_date: $registration_date
            address: $address
            score: $score
          ) {
            id
            name
            phone_number
            registration_date
            address,
            score
          }
        }
      `;

      const { mutate: createCustomer, error, loading } = useMutation(createCustomerMutation,
        () => ({
          variables: {
            name: name.value,
            phone_number: phone_number.value,
            registration_date: formatDate(registration_date.value),
            address: address.value,
            score: score.value,
          },
        })
      )

      const handleSubmit = async () => {
        try {
          await createCustomer();
          messageSuccess.value = 'Customer created successfully'
          resetField();
        } catch (error) {
          console.error(error)
        //   alert('Error creating user')
        }
      }

      // Validate
      const validateFunc = (index) => {
        if(index === 1) {
          if(name.value.length > 0) {
              showErrorMessage.value = { name: ''}
          } else {
            showErrorMessage.value = {
              name: "Name Is Required"
            }
          }
        }
        if(index === 2) {
          if(phone_number.value.length > 0) {
            showErrorMessage.value = { phone_number: ''}
          } else {
            showErrorMessage.value = {
              phone_number: "Phone Is Required"
            }
          }
        }
        if(phone_number.value.length > 0 && name.value.length > 0)  {
          btnDisabled.value = false;
        } else {
          btnDisabled.value = true;
        }
      }
      const onchangeInput = (event, index) => {
        if(index === 1) {
          name.value = event.target.value
        } else if(index === 2) {
          phone_number.value = event.target.value
        }
        validateFunc(index);
      }

      const resetField = () => {
        name.value = '';
        phone_number.value = '';
        registration_date.value = '';
        address.value ='';
        score.value = '';
      }

      return {
        name,
        phone_number,
        btnDisabled,
        registration_date,
        address,
        score,
        handleSubmit,
        messageSuccess,
        showErrorMessage,
        validateFunc,
        onchangeInput,
        error,
        loading,
      }
    },
  }
  </script>

