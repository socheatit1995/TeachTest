<script setup>
import WidgetsStatsA from './../widgets/WidgetsStatsTye.vue'
import { ref, watch } from 'vue'
import { useQuery } from '@vue/apollo-composable'
import gql from 'graphql-tag'
const tatalDashboard = ref(null);

const QUERY_DASHBOARD = gql`
    query {
      totalDashboar {
        totalUser, totalPuchase, totalCustomer
      }
    }
`;
  const { result, refetch }  = useQuery(QUERY_DASHBOARD);
  tatalDashboard.value = result?.value?.totalDashboar; 
  console.log('test ', tatalDashboard.value) 
  watch(result, value => {
    tatalDashboard.value = value?.totalDashboar 
  })
</script>

<template>
  <div>
    <WidgetsStatsA class="mb-4" :data="tatalDashboard"/>
  </div>
</template>


