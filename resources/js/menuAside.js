import {
    mdiMonitor,
    mdiTable,
    mdiMessage,
    mdiMessageText,
    mdiForum, mdiCog, mdiTextLong, mdiAccount, mdiPhone,
} from "@mdi/js";

export default [
  {
    route: 'admin.admin',
    icon: mdiMonitor,
    label: "Главная",
  },
  {
    route: 'admin.channels',
    label: "Каналы",
    icon: mdiTable,
  },
  {
    route: 'admin.users',
    label: "Пользователи",
    icon: mdiAccount,
  },
  {
    route: 'admin.callbacks',
    label: "Обратные вызовы",
    icon: mdiPhone,
  },
  {
    label: "Чаты",
    icon: mdiForum,
    menu: [
      {
        icon: mdiMessageText,
        label: "пользователи",
      },
      {
        icon: mdiMessage,
        label: "поддержка",
        route: 'admin.support',
      },
    ],
  },
{
label: "Настройки",
icon: mdiCog,
menu: [
  {
    icon: mdiTextLong,
    label: "Тематики",
    route: 'admin.topics',
  },
],
},
];
