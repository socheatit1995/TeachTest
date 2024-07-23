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
            <strong>PURCHASES</strong>
          </CCardHeader>
          <CCardBody>
                <CForm @submit.prevent="handleSubmit" class="p-4">
                  <h1>Create Purchase</h1>
                  <CInputGroup class="mb-3">
                    <CFormInput placeholder="Customer" v-model="searchQuery" autocomplete="Name or Phone Number" @input="onSearchCustomer($event)"/>
                    <CListGroup v-if="searchResults?.length > 0 && showFale" class="list_float">
                        <CListGroupItem @click.prevent="selectCustomer(customer)" v-for="customer in searchResults" :key="customer.id">{{ customer.name }} ({{ customer.phone_number }})</CListGroupItem>
                    </CListGroup>
                    <CFormFeedback v-if="showErrorMessage?.customer" class="invalid-feedback"> {{  showErrorMessage?.customer  }} </CFormFeedback>
                </CInputGroup>
                  <CInputGroup class="mb-3">
                    <CFormInput  type="number" placeholder="Amount" v-model="amount" @input="onchangeAmount($event)"/>
                    <CFormFeedback v-if="showErrorMessage?.amount" class="invalid-feedback"> {{  showErrorMessage?.amount  }} </CFormFeedback>
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
  import { ref, computed } from 'vue'
  import { useMutation, useQuery } from '@vue/apollo-composable'
  import gql from 'graphql-tag'
  import router from '@/router'

  export default {
    setup () {
      const customerId = ref('')
      const amount = ref('')
      const messageSuccess = ref('')
      const customers = ref([])
      const searchQuery = ref('')
      const selectedCustomer = ref(null)
      const showFale = ref(true);
      const showErrorMessage = ref({});
      const btnDisabled = ref(true);

      const SerchCustomer = gql`
        query customer($name: String!){
            customer(search: { name: $name}){
                id, name, phone_number
            }
        }
      `;



      const { result} = useQuery(SerchCustomer,() => ({
            name: searchQuery.value
        }))

      const onSearchCustomer = async (event) => {
        searchQuery.value = event.target.value
        checkValidity(1);

        showFale.value = true;
            try {
              const customersData = await result.value?.customer ?? [];
                customers.value = customersData.filter(customer => {
                        return customer.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                            customer.phone_number.includes(searchQuery.value)
                    })
            } catch (error) {
                console.error(error)
            //   alert('Error creating user')
            }
      }

      const searchResults = computed(() => {
      if (!searchQuery.value) return []
      return customers.value.filter(customer => {
        return customer.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
               customer.phone_number.includes(searchQuery.value)
      })
    })
    const selectCustomer = (customer) => {
        selectedCustomer.value = customer,
        searchQuery.value = customer.name
        customerId.value = customer.id
        showFale.value = false
      }

    const onchangeAmount = (event) => {
        amount.value = event.target.value
        checkValidity(2);
    }

      const createPurchaseMutation = gql`
        mutation createPurchase (
          $customerId: ID!
          $amount: Float!
        ) {
            createPurchase (
            customerId: $customerId
            amount: $amount
          ) {
            id
          }
        }
      `;


      const { mutate: createPurchase, error, loading } = useMutation(createPurchaseMutation,
        () => ({
          variables: {
            customerId: Number(customerId.value),
            amount: Number(amount.value),
          },
        })
      )

      const handleSubmit = async () => {

        try {
          await createPurchase();
          messageSuccess.value = 'Purchase created successfully'
          resetField();
          router.replace('/purchase')
        } catch (error) {
          console.error(error)
        }
      }

      const checkValidity = (index) => {
        if(index === 1) {
          if(searchQuery.value.length > 0) {
            showErrorMessage.value.customer = '';
          } else {
              showErrorMessage.value = {
                customer: "Customer Is Required"
              }
          }
        }
        if(index === 2) {
          if (amount.value.length > 0) {
              showErrorMessage.value.amount = '';
          } else {
            showErrorMessage.value = {
                amount: "Amount Is Required"
              }
          }
        }
        if(searchQuery.value.length > 0 && amount.value.length > 0) {
          btnDisabled.value = false;
        } else {
          btnDisabled.value = true;
        }
      }
      const resetField = () => {
        searchQuery.value = '';
        customerId.value = '';
        amount.value = '';
      }

      return {
        showFale,
        searchQuery,
        searchResults,
        showErrorMessage,
        messageSuccess,
        customerId,
        amount,
        error,
        loading,
        btnDisabled,
        onSearchCustomer,
        selectCustomer,
        handleSubmit,
        onchangeAmount,
        checkValidity
      }
    },
  }
  </script>
  <style scoped >
  .list_float {
    position: absolute;
    top: 40px;
    background: #fff;
    z-index: 999;
    min-width: 500px

  }
  .list_float li {
        cursor: pointer;
  }
  .invalid-feedback {
    display: block !important;
  }
</style>
