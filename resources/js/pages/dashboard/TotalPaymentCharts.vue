<script setup>
import * as echarts from "echarts";
import { ref, watch, onBeforeUnmount } from "vue";
import { useDashboardRepository } from "@/store/DashboardRepository";

// Use the DashboardRepository store
let DashboardRepository = useDashboardRepository();
const profitsData = ref([]);


// Watch the data from the repository and update the chart when it changes
watch(
  () => DashboardRepository.dashboards.monthlyProfit,
  async (newData) => {
    // Ensure newData is an array, or use a fallback
    profitsData.value = Array.isArray(newData) ? newData : new Array(12).fill(0);

    await updateChart(); // Call the update function
  },
  { immediate: true }
);


// Function to update the chart
async function updateChart() {
  const chartElement = document.getElementById("income");
  const myChart = echarts.init(chartElement);

  const months = [
    "January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"
  ];

  // Format the data (filtering out months with no profit)
  const formattedData = profitsData.value
    .map((value, index) => ({
      name: months[index],
      value: value,
    }))
    .filter(item => item.value > 0); // Keep only months with profit


  const option = {
    title: {
      text: "This Year Profits",
      left: "left",
      top: "10",
      textStyle: {
        fontSize: 18,
        fontWeight: "normal",
        color: "#333",
        fontFamily: "Calibri, sans-serif",
      },
    },
    color: ["#112F53", "#8B5CF6", "#A78BFA", "#C4B5FD", "#7C3AED"],
    tooltip: {
      show: true,
      backgroundColor: "rgba(0, 0, 0, .8)",
      formatter: (params) => `${params.name}: ${params.value} profit`,
    },
    series: [
      {
        name: "Monthly Profits",
        type: "pie",
        radius: "50%",
        center: ["50%", "50%"],
        data: formattedData,
        itemStyle: {
          emphasis: {
            shadowBlur: 10,
            shadowOffsetX: 0,
            shadowColor: "rgba(0, 0, 0, 0.5)",
          },
        },
      },
    ],
  };

  myChart.setOption(option);

  // Resize the chart when the window is resized
  window.addEventListener("resize", myChart.resize);

  // Cleanup resize event listener on unmount
  onBeforeUnmount(() => {
    window.removeEventListener("resize", myChart.resize);
    myChart.dispose();
  });
}
</script>

<template>
  <div class="shadow-md bg-white rounded-xl d-flex justify-center chart-container">
    <div id="income" class="chart"></div>
  </div>
</template>

<style scoped>
.chart-container {
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
}

.chart {
  width: 100%;
  height: 100%;
}
</style>
