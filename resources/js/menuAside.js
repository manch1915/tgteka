import {
    mdiMonitor,
    mdiTable,
    mdiMessage,
    mdiMessageText,
    mdiForum, mdiCog, mdiTextLong,
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
    label: "тематики",
    route: 'admin.topics',
  },
],
},
];
