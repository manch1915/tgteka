<script setup>
const props = defineProps({
    title: String,
    description: String
})
</script>

<template>
    <div class="channel_card">
        <div class="grid">
<!-- TODO badge from naive ui           -->
            <div class="flex grid-element flex-col items-center justify-center gap-y-3">
                <div class="white-point"></div>
                <div class="flex rating text-white text-sm font-normal font-['Poppins'] leading-tight">
                    <img src="/images/gavat.svg" alt="">
                    <p>34.3</p>
                </div>
                <div class="avatar">
                    <img src="/images/avatar.jpg" alt="avatar">
                </div>
            </div>
            <div class="grid-element">
                <div class="flex flex-col gap-y-2 justify-between">
                    <div>
                    <p class="rate_catalog inline text-violet-100 text-sm font-normal font-['Open Sans']">#2477 в каталоге</p>
                    </div>
                    <h1 class="text-white text-xl font-bold font-['Open Sans'] leading-relaxed">{{title}}</h1>
                    <p class="text-white box-content line-clamp-3  text-sm font-normal font-['Poppins'] break-all leading-tight">{{description}}</p>
                </div>
            </div>
            <div class="grid-element flex flex-col items-center justify-center">
                <div class="flex w-full justify-around text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">
                    <button class="watch flex items-center gap-x-1.5">Канал в каталоге <i class="block eye"></i></button>
                    <button class="edit">Редактировать канал</button>
                    <button class="orders flex items-center gap-x-1.5">К заявкам <i class="block inkarrow"></i></button>
                </div>
            </div>
        </div>
        <div class="flex justify-center py-6 unwrap">
            <button class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal flex">Развернуть <i class="block arrow-down"></i></button>
        </div>
    </div>
</template>

<style scoped lang="scss">
.channel_card{
    border-radius: 0 40px 40px 40px;
    border: 1px solid #6522D9;
    .unwrap{
        border-radius: 0 0 40px 40px;

        background: #301864;
    }
    .grid{
        border-radius: 0 40px 0 0;
        background: linear-gradient(to bottom, #131733, #343850);
        .grid-element{
            min-width: 0;
            padding: 20px;
            &:not(:last-child) {
                border-right: 1px solid #6522D9;
            }
        }
        grid-template-columns: 1fr 3fr 7fr;
        div{
            .white-point{
                width: 15px;
                height: 15px;
                background: #FFFFFF;
                border-radius: 50%;
            }
            .avatar{
                width: 60px;
                height: 60px;
                img{
                    border-radius: 50%;
                }
            }
        }
    }
}
.watch,.edit {
    padding: 15px 25px;
    border-radius: 30px;
    border: 1px solid #6522D9;
}
.orders {
    padding: 15px 25px;
    border-radius: 30px;
    background: rgb(135, 41, 255);
}
.rate_catalog{
    border-radius: 30px;
    background: #363B59;
    padding: 4px 10px;
}
.inkarrow{
    width: 24px;
    height: 24px;
    background: url("/images/inkarrow.svg");
}
.eye{
    width: 24px;
    height: 24px;
    background: url("/images/eye.svg");
}
.arrow-down{
    width: 24px;
    height: 24px;
    background: url("/images/arrow-down.svg");
}
</style>
