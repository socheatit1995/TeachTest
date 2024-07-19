<template>
  <div class="wrapper min-vh-100 d-flex flex-row align-items-center">
    <CContainer>
      <CRow class="justify-content-center">
        <CCol :md="8">
          <CCardGroup>
            <CCard class="p-4">
              <CCardBody>
                <CForm @submit.prevent="login">
                  <h1>Login</h1>
                  <p class="text-body-secondary">Sign In to your account</p>
                  <CInputGroup class="mb-3">
                    <CInputGroupText>
                      <CIcon icon="cil-user" />
                    </CInputGroupText>
                    <CFormInput
                      placeholder="Email"
                      autocomplete="email"
                      v-model="email"
                    />
                  </CInputGroup>
                  <CInputGroup class="mb-4">
                    <CInputGroupText>
                      <CIcon icon="cil-lock-locked" />
                    </CInputGroupText>
                    <CFormInput
                      type="password"
                      placeholder="Password"
                       v-model="password"
                      autocomplete="current-password"
                    />
                  </CInputGroup>
                  <CRow>
                    <CCol :xs="6">
                      <CButton type="submit" color="primary" class="px-4"> Login </CButton>
                    </CCol>
                    <!-- <CCol :xs="6" class="text-right">
                      <CButton color="link" class="px-0">
                        Forgot password?
                      </CButton>
                    </CCol> -->
                  </CRow>
                </CForm>
              </CCardBody>
            </CCard>
          </CCardGroup>
        </CCol>
      </CRow>
    </CContainer>
  </div>
</template>
<script setup>
import gql from 'graphql-tag';
import { useMutation } from '@vue/apollo-composable'
import { computed, watchEffect } from 'vue';

// const ALL_USER_QUERY = gql`
//   mutation {
//     login (input: {email: "abechtelar@example.com", password: "12345678a"}) {
//       token
//       expires_at
//     }

//   }
// `;

// const { result } = useMutation(ALL_USER_QUERY);

// const users = computed(() => {
//   return result.value?.users || [];
// });

// watchEffect(() => {
//   console.log(users.value);
// })

const login = async () => {
  try {
    const response = await this.$apollo.mutate({
      mutation: gql`
            mutation Login($email: String!, $password: String!) {
              login(email: $username, password: $password) {
                token,
                expires_at,
                user {
                  id, name, username, email, created_at, updated_at
                }
              }
            }
          `,
          variables: {
            email: this.email,
            password: this.password,
          },
        });

        const token = response.data.login.token;
        // Store the token in local storage or a cookie
        localStorage.setItem('token', token);

        // Redirect to a protected route or update the UI
        this.$router.replace('/dashboard');
  } catch (error) {
    console.error(error);
  }
}

</script>
