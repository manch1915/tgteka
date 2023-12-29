import { defineStore } from 'pinia'

export const useProfileButtons = defineStore('profileButtons', {
    state: () => ({
        activeButton: 'Личные данные',
        routes: {
            'Личные данные': '/profile/personal-data',
            'Общий баланс': '/profile/total-balance',
            'Пополнение средств': '/profile/replenishment',
            'Вывод средств': '/profile/withdraw',
            'История транзакций': '/profile/transactions/history',
            'История уведомлений': '/profile/notifications/history',
            'Настройка уведомлений': '/profile/notifications-setting',
            'Изменение пароля': '/profile/change-password',
        }
    }),
    actions: {
        setActiveButton(buttonName){
            this.activeButton = buttonName
        },
        setActiveButtonByUrl(url) { // Add this
            for (const buttonName in this.routes) {
                if (this.routes[buttonName] === url) {
                    this.activeButton = buttonName
                    break;
                }
            }
        }
    }
})
