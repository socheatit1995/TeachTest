<template>
    <CRow>
      <CCol :xs="12">
        <CCard class="mb-4">
          <CCardHeader>
            <strong>CUSTOMER</strong>
          </CCardHeader>
          <CCardBody>
                <CForm @submit.prevent="handleSubmit" class="p-4">
                  <h1>Create Customer</h1>
                  <p class="text-body-secondary" v-if="messageSuccess !== ''">{{ messageSuccess }}</p>
                  <CInputGroup class="mb-3">
                    <CFormInput placeholder="Name" v-model="name" autocomplete="name" required/>
                  </CInputGroup>
                  <CInputGroup class="mb-3">
                    <CFormInput placeholder="PhoneName" v-model="phone_number" autocomplete="PhoneName" required/>
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
                    <CButton type="submit" color="primary" shape="rounded-pill"> Save </CButton>
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
        if(name.value === '' && phone_number.value === '') {
            alert('Please Name and Phone Number in all fields')
            return ;
        }
        console.log('my date', formatDate(registration_date.value));
        try {
          await createCustomer();
          messageSuccess.value = 'Customer created successfully'
          router.replace('/customers')
        } catch (error) {
          console.error(error)
        //   alert('Error creating user')
        }
      }
      
      return {
        name,
        phone_number,
        registration_date,
        address,
        score,
        handleSubmit,
        error,
        loading,
      }
    },
  }
  </script>
  