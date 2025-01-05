<script setup>
import * as echarts from "echarts";
import { onMounted, nextTick, watch } from "vue";
import { useDashboardRepository } from "@/store/DashboardRepository";

let DashboardRepository = useDashboardRepository();
DashboardRepository.fetchDashboardData();


// Watch for changes in data
watch(
    () => DashboardRepository.dashboards.monthly_earning_expenses,
    () => {
        updateChart();
    },
    { immediate: true }
);

async function updateChart() {
    await nextTick();

    const chartElement = document.getElementById("bar");
    if (!chartElement) {
        return;
    }

    const myChart = echarts.init(chartElement);

    // Ensure both monthly earnings and expenses have 12 months of data
    const allMonths = [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];

    // Fill in missing months with null (this will avoid rendering bars for empty months)
    const fillMissingData = (data) => {
        const filledData = new Array(12).fill(null); // Create an array with 12 months, initialized to null
        data.forEach((value, index) => {
            if (index < 12) filledData[index] = value; // Place the data in the correct month index
        });
        return filledData;
    };

    // Ensure monthlyEarnings and monthlyExpenses arrays are filled with data for all 12 months
    const monthlyEarnings = fillMissingData(DashboardRepository.dashboards.monthly_earning_expenses.earnings);
    const monthlyExpenses = fillMissingData(DashboardRepository.dashboards.monthly_earning_expenses.expenses);

    // Chart configuration based on the new design
    const option = {
    title: {
        text: "This Year Earnings and Expenses", // Title text
        left: "center", // Centered title
        top: "10", // Adjusts the vertical position
        textStyle: {
            fontSize: 18,
            fontWeight: "bold",
            color: "#333", // Text color
            fontFamily: "Calibri, sans-serif",
        },
    },
    legend: {
        borderRadius: 0,
        orient: "horizontal",
        x: "right",
        data: ["Earnings", "Expenses"],
    },
    grid: {
        left: "8px",
        right: "8px",
        bottom: "0",
        containLabel: true,
    },
    tooltip: {
        show: true,
        backgroundColor: "rgba(0, 0, 0, .8)",
    },
    xAxis: [
        {
            type: "category",
            data: allMonths,
            axisTick: {
                alignWithLabel: true,
            },
            splitLine: {
                show: false,
            },
            axisLabel: {
                color: "#333",
                interval: 0,
                rotate: 30,
            },
            axisLine: {
                show: true,
                color: "#333",
                lineStyle: {
                    color: "#333",
                },
            },
        },
    ],
    yAxis: [
        {
            type: "value",
            axisLabel: {
                color: "#333",
            },
            axisLine: {
                show: false,
                color: "#333",
                lineStyle: {
                    color: "#333",
                },
            },
            min: 0,
            splitLine: {
                show: true,
                interval: "auto",
            },
        },
    ],
    series: [
        {
            name: "Earnings",
            data: monthlyEarnings,
            label: { show: false, color: "#8B5CF6" },
            type: "bar",
            color: "#112F53",
            smooth: true,
            itemStyle: {
                emphasis: {
                    shadowBlur: 10,
                    shadowOffsetX: 0,
                    shadowOffsetY: -2,
                    shadowColor: "rgba(0, 0, 0, 0.3)",
                },
            },
        },
        {
            name: "Expenses",
            data: monthlyExpenses,
            label: { show: false, color: "#0168c1" },
            type: "bar",
            barGap: 0,
            color: "#B8C1CB",
            smooth: true,
            itemStyle: {
                emphasis: {
                    shadowBlur: 10,
                    shadowOffsetX: 0,
                    shadowOffsetY: -2,
                    shadowColor: "rgba(0, 0, 0, 0.3)",
                },
            },
        },
    ],
};

myChart.setOption(option);

    myChart.setOption(option);
}
</script>

<template>
    <div class="shadow-md pt-8 bg-white rounded-xl d-flex justify-center">
        <div id="bar" style="width: 100%; height: 300px;"></div>
    </div>
</template>