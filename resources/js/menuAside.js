import {
    mdiMonitor,
    mdiTable,
    mdiMessage,
    mdiForum, mdiCog, mdiTextLong, mdiAccount, mdiPhone, mdiCreditCardOutline, mdiBugOutline,
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
    route: 'admin.payouts',
    label: "Выводы",
    icon: mdiCreditCardOutline,
  },
  {
    route: 'admin.reports',
    label: "Жалобы",
    icon: mdiBugOutline,
  },
  {
    label: "Чаты",
    icon: mdiForum,
    menu: [
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
{
    label: "Настройки",
    icon: mdiCog,
    route: 'admin.settings',
}
],
},
];
