<!-- Resources/js/Pages/Dashboard.vue -->
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import Card from "@/Components/Auth/Card.vue";

import {
  FileTextOutlined,
  SwapOutlined,
  FileDoneOutlined,
  IdcardOutlined,
  GiftOutlined,
  PhoneOutlined,
  UserOutlined,
  ReadOutlined,
  AppstoreOutlined,
  PictureOutlined,
  CalendarOutlined,
  SettingOutlined,
  DashboardOutlined,
  SafetyOutlined,
  TeamOutlined,
  MailOutlined,
  QuestionCircleOutlined,
  HistoryOutlined,
  BookOutlined,
  GlobalOutlined,
  BarChartOutlined,
  PlusCircleOutlined,
  NotificationOutlined,
  AuditOutlined,
  BuildOutlined,
  HomeOutlined,
  StarOutlined,
  RocketOutlined,
  TrophyOutlined,
  UsergroupAddOutlined,
  PieChartFilled,
  PieChartOutlined,
  CarOutlined,
  PlayCircleOutlined,
  QuestionOutlined,
  QuestionCircleFilled,
  QuestionCircleTwoTone,
  PhoneFilled,
  PhoneTwoTone,
  FolderAddFilled,
  FolderOpenFilled,
  InfoCircleFilled,
  InboxOutlined,
  LineChartOutlined,
  RiseOutlined,
  FallOutlined,
  UserAddOutlined
} from '@ant-design/icons-vue'

// Props from Inertia
const props = defineProps({
  auth: Object,
  stats: Object,
  charts: Object
});

const activeKey = ref('1');

// Function to check if user has a permission
const can = (permission) => {
  if (props.auth?.user?.roles?.some(role => role.name === 'Super Admin')) {
    return true;
  }
  return props.auth?.permissions?.includes(permission);
};

</script>

<template>

  <Head title="Dashboard" />
  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <div>
          <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
            Dashboard
          </h2>
        </div>
      </div>
    </template>

    <div class="py-4">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-4">

        <!-- Main Card with Tabs -->
        <div
          class="overflow-hidden bg-white dark:bg-gray-800 shadow-lg rounded-xl border border-gray-100 dark:border-gray-700">
          <div class="p-6">

            <!-- Professional Quick Links Tabs -->
            <div class="dashboard-tabs">
              <a-tabs v-model:activeKey="activeKey" class="custom-tabs">

                <a-tab-pane key="1">
                  <template #tab>
                    <div class="flex items-center gap-2">
                      <StarOutlined class="text-teal-500" />
                      <span>Quick Links</span>
                    </div>
                  </template>

                  <div class="mt-6">
                    <div class="max-h-[500px] overflow-y-auto pr-2">
                      <div class="flex flex-wrap gap-4">

                        <Card title="Access Control Management" v-if="can('manage access control')"
                          subTitle="Create, edit and delete of Admin Access Control"
                          routeName="admin.rolesAndPermission">
                          <template #icon>
                            <SettingOutlined style="font-size: 24px; color: #14b8a6;" />
                          </template>
                        </Card>

                        <Card title="Staff Role Assignment" v-if="can('manage staff access control')"
                          subTitle="Create, edit and delete of Staff Access Control" routeName="staff.index">
                          <template #icon>
                            <TeamOutlined style="font-size: 24px; color: #14b8a6;" />
                          </template>
                        </Card>

                        <!-- <Card title="Contacts Management" v-if="can('manage contact us')"
                          subTitle="Create, edit & delete Contact messages" routeName="contact.index">
                          <template #icon>
                            <PhoneFilled style="font-size: 24px; color: #14b8a6;" />
                          </template>
                        </Card> -->
                       
                        <Card title="My Account Management" subTitle="Manage your profile and account settings"
                          routeName="profile.edit">
                          <template #icon>
                            <UserAddOutlined style="font-size: 24px; color: #14b8a6;" />
                          </template>
                        </Card>
                      </div>
                    </div>
                  </div>
                </a-tab-pane>

              </a-tabs>
            </div>
          </div>
        </div>

      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
/* Professional Tabs Styling */
.dashboard-tabs :deep(.ant-tabs-nav) {
  margin-bottom: 0;
  border-bottom: 2px solid #e5e7eb;
}

.dark .dashboard-tabs :deep(.ant-tabs-nav) {
  border-bottom-color: #374151;
}

.dashboard-tabs :deep(.ant-tabs-tab) {
  padding: 12px 24px;
  font-size: 0.95rem;
  font-weight: 500;
  transition: all 0.3s;
  margin: 0 4px;
  border-radius: 8px 8px 0 0;
  color: #6b7280;
}

.dark .dashboard-tabs :deep(.ant-tabs-tab) {
  color: #9ca3af;
}

.dashboard-tabs :deep(.ant-tabs-tab:hover) {
  color: #14b8a6;
}

.dashboard-tabs :deep(.ant-tabs-tab-active) {
  color: #14b8a6;
}

.dashboard-tabs :deep(.ant-tabs-tab-active .ant-tabs-tab-btn) {
  color: #14b8a6;
  font-weight: 600;
}

.dashboard-tabs :deep(.ant-tabs-ink-bar) {
  background: linear-gradient(90deg, #14b8a6, #10b981);
  height: 3px;
  border-radius: 3px;
}

.dashboard-tabs :deep(.ant-tabs-tab-btn) {
  display: flex;
  align-items: center;
  gap: 8px;
}


/* Custom scrollbar */
.scrollbar {
  scrollbar-width: thin;
  scrollbar-color: #14b8a6 #f3f4f6;
}

.scrollbar::-webkit-scrollbar {
  width: 8px;
}

.scrollbar::-webkit-scrollbar-track {
  background: #f3f4f6;
  border-radius: 8px;
}

.scrollbar::-webkit-scrollbar-thumb {
  background: #14b8a6;
  border-radius: 8px;
}

.scrollbar::-webkit-scrollbar-thumb:hover {
  background: #0f766e;
}

/* Custom animations */
@keyframes pulse {

  0%,
  100% {
    opacity: 1;
  }

  50% {
    opacity: 0.5;
  }
}

.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Chart container enhancements */
:deep(.pie-chart-container),
:deep(.bar-chart-container) {
  min-height: 300px;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .dashboard-tabs :deep(.ant-tabs-tab) {
    padding: 8px 12px;
    font-size: 0.85rem;
  }

  .dashboard-tabs :deep(.ant-tabs-tab-btn) {
    gap: 4px;
  }

  .grid {
    gap: 1rem;
  }
}

/* Hover effects for cards */
.bg-white,
.bg-gradient-to-r {
  transition: all 0.3s ease;
}

/* Chart card hover effect */
.chart-card:hover {
  transform: translateY(-2px);
}

/* Global styles for Ant Design tabs to match theme */
.ant-tabs-tab-btn {
  transition: color 0.3s;
}

.ant-tabs-tab .anticon {
  font-size: 1rem;
}

/* Dark mode support for tabs */
.dark .ant-tabs-nav::before {
  border-bottom-color: #374151;
}

.dark .ant-tabs-tab {
  color: #9ca3af;
}

.dark .ant-tabs-tab:hover {
  color: #14b8a6;
}

.dark .ant-tabs-tab-active .ant-tabs-tab-btn {
  color: #14b8a6 !important;
}

/* Chart.js canvas styling */
canvas {
  max-height: 300px;
  width: 100% !important;
}
</style>
