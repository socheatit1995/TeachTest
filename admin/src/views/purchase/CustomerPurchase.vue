<template>
    <CRow>
      <CCol :xs="12">
        <CCard class="mb-4">
          <CCardHeader>
            <strong>PURCHASES</strong>
          </CCardHeader>
          <CCardBody>
                <CForm @submit.prevent="handleSubmit" class="p-4">
                  <h1>Create Purchase</h1>
                  <p class="text-body-secondary" v-if="messageSuccess !== ''">{{ messageSuccess }}</p>
                  <CInputGroup class="mb-3">
                    <CFormInput placeholder="Customer" v-model="searchQuery" autocomplete="Name or Phone Number" @input="onSearchCustomer($event)"/>
                    <CListGroup v-if="searchResults?.length > 0 && showFale" class="list_float">
                        <CListGroupItem @click.prevent="selectCustomer(customer)" v-for="customer in searchResults" :key="customer.id">{{ customer.name }} ({{ customer.phone_number }})</CListGroupItem>
                    </CListGroup>
                </CInputGroup>
                  <CInputGroup class="mb-3">
                    <CFormInput  type="number" placeholder="Amount" v-model="amount" autocomplete="PhoneName" required/>
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
      const transactionAmount = ref(0)
      const showFale = ref(true);

      const SerchCustomer = gql`
        query customer($name: String!){
            customer(search: { name: $name}){
                id, name, phone_number
            }
        }
      `;
        const { result, refetch } = useQuery(SerchCustomer,() => ({
            name: searchQuery.value
        }))

      const onSearchCustomer = async (event) => {
        searchQuery.value = event.target.value
        showFale.value = true;
            try {
                customers.value = await result.value?.customer ?? [];
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
        if(customerId.value === '' && amount.value === '') {
            alert('Please Select Customer and Input Amount in all fields')
            return ;
        }
        try {
          await createPurchase();
          messageSuccess.value = 'Purchase created successfully'
          router.replace('/purchase')
        } catch (error) {
          console.error(error)
        //   alert('Error creating user')
        }
      }

      return {
        showFale,
        searchQuery,
        searchResults,
        onSearchCustomer,
        selectCustomer,
        customerId,
        amount,
        handleSubmit,
        error,
        loading,
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
</style>
