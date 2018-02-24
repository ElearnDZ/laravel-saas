<template>
<div class="w-full">
<ul class="list-reset flex justify-end w-full max-w-sm mx-auto">
    <li :class="tabMargin">
        <a @click.prevent="switchToTab('general')" :class="tabClass('general')">General</a>
    </li>
    <li v-show="user.pastTrial || activeSubscription" class="-mb-px mr-auto">
        <a @click.prevent="switchToTab('vat')" :class="tabClass('vat')">VAT</a>
    </li>
    <li v-show="activeSubscriptionAndNotPastTrial" class="-mb-px">
        <a class="text-red-light hover:text-red-dark" @click.prevent="switchToTab('cancel')" :class="tabClass('cancel')">Cancel</a>
    </li>
</ul>
<form class="formbox mb-8" :action="action" method="POST">
    <input type="hidden" name="_method" value="PATCH">
    <input type="hidden" name="_token" :value="token">
    <input type="hidden" name="stripeCardToken" :value="updateToken">

    <div v-show="activeTab == 'general'">
        <div v-show="!user.onTrial && !user.pastTrial" class="mb-4">
            <label for="name" class="form-label">Credit card</label>

            <div class="mb-4">
                <p class="my-0">
                    <span class="flex justify-between">
                        <span>{{ user.card_brand }} ending in {{ user.card_last_four }}</span>
                        <a v-if="activeSubscription" class="cursor-pointer" @click.prevent="openStripe">Update credit card</a>
                    </span>
                </p>
            </div>
        </div>

        <div v-if="user.onTrial || user.pastTrial">
            <div class="mb-4">
                <label for="plan" class="form-label">Upgrade your trial</label>

                <div>
                    <a href="#" class="block btn btn-positive">Choose a plan</a>
                </div>
            </div>
        </div>

        <div v-else-if="user.onGracePeriod">
            <div class="mb-4">
                <label for="plan" class="form-label">Subscription status</label>

                <div>
                   <p class="mt-0 mb-4">
                       Your subscription has been cancelled and we will no longer charge you. If you wish, you may resume your subscription with the button below.
                    </p>

                    <p>
                        Your data will be deleted on {{ formatDate(subscription.ends_at) }}, in accordance with the 
                        <a href="https://www.eugdpr.org/" rel="nofollow">European GDPR regulation.</a>
                    </p>
                </div>
            </div>

            <div class="mb-4">
                <label for="plan" class="form-label">Resume subscription</label>

                <div>
                    <a href="/settings/billing/resume" class="block btn btn-positive">Resume my subscription</a>
                </div>
            </div>
        </div>

        <div v-else>
            <div class="mb-4">
                <label for="plan" class="form-label">Current plan</label>

                <div>
                    <select id="plan" name="plan">
                        <option v-for="plan in plans" :key="plan.id" :value="plan.name" :selected="plan.name == user.subscription.stripe_plan">{{ plan.pretty_name }}</option>
                    </select>
                </div>

                <p class="text-sm text-grey-dark mt-2 mb-0">You can change your plan at any time. You'll be prorated accordingly.</p>
            </div>
        </div>
    </div>

    <div v-show="activeTab == 'vat'">
        <div class="mb-4">
            <p class="mb-4">
                If you are a European business and want to have the VAT removed from your bill, you can enter
                your VAT ID below.
            </p>

            <label for="vat" class="form-label">VAT</label>

            <div>
                <input id="vat" type="text" name="vat" v-model="formData.vat">

                <span v-if="errors.vat" class="alert alert-danger">
                    {{ errors.vat[0] }}
                </span>
            </div>
        </div>

        <div class="mb-4">
            <p class="mb-4">
                To allow us to write a proper invoice we also require you to enter your address below.
            </p>

            <label for="street" class="form-label">Street</label>

            <div>
                <input id="street" type="text" name="street" v-model="formData.street">

                <span v-if="errors.street" class="alert alert-danger">
                    {{ errors.street[0] }}
                </span>
            </div>
        </div>

        <div class="flex justify-between">
            <div class="mb-4 w-full md:w-1/3">
                <label for="zip" class="form-label">ZIP</label>

                <div>
                    <input id="zip" type="text" name="zip" v-model="formData.zip">

                    <span v-if="errors.zip" class="alert alert-danger">
                        {{ errors.zip[0] }}
                    </span>
                </div>
            </div>

            <div class="mb-4 ml-4 w-full md:w-2/3">
                <label for="city" class="form-label">City</label>

                <div>
                    <input id="city" type="text" name="city" v-model="formData.city">

                    <span v-if="errors.city" class="alert alert-danger">
                        {{ errors.city[0] }}
                    </span>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <label for="country" class="form-label">Country</label>

            <div>
                <country-list id="country" name="country" v-model="formData.country"></country-list>

                <span v-if="errors.country" class="alert alert-danger">
                    {{ errors.country[0] }}
                </span>
            </div>
        </div>

        <div v-if="user.onTrial || user.pastTrial">
            <div class="mb-4">
                <label for="plan" class="form-label">Upgrade your trial</label>

                <div>
                    <a href="#" class="block btn btn-positive">Choose a plan</a>
                </div>
            </div>
        </div>
    </div>

    <div v-show="activeTab == 'cancel'">
        <div>
            <label for="plan" class="form-label">Cancel your subscription</label>

            <div>
                <a :href="actionCancel" class="block btn btn-negative">Cancel my subscription</a>
            </div>

            <p class="text-sm text-grey-dark mt-2 mb-0"><strong>We'll miss you!</strong> Your account will remain active for the rest of the billing period and you will not be charged again.</p>
        </div>
    </div>
</form>
</div>
</template>

<script>
import queryString from 'query-string'
import moment from 'moment'

export default {
    props: ['action', 'actionCancel', 'plans', 'errors'],

    methods: {
        switchToTab(tab) {
            this.activeTab = tab
            window.history.pushState('', '', `?tab=${tab}`)
        },

        tabClass(tab) {
            const isActive = this.activeTab == tab ? 'tab-active' : ''
            return `tab ${isActive}`
        },
        
        formatDate(date) {
            return moment(date).format("DD MMMM YYYY")
        }
    },

    computed: {
        tabMargin() {
            const baseClass = '-mb-px'
            const marginClass = !this.activeSubscription ? 'mr-auto' : 'mr-1'

            return `${baseClass} ${marginClass}`
        },

        activeSubscriptionAndNotPastTrial() {
            return this.activeSubscription && !this.user.pastTrial
        },

        activeSubscription() {
            return !this.user.onTrial && !this.user.onGracePeriod
        }
    },

    data() {
        return {
            user: window.User,
            stripe: null,
            token: Configuration.csrfToken,
            updateToken: '',
            activeTab: '',
            formData: {
                vat: '',
                // street: window.User.address.street,
                // zip: window.User.address.zip,
                // city: window.User.address.city,
                // country: window.User.address.country,
            }
        }
    },

    created() {
        const query = queryString.parse(location.search)
        this.activeTab = query.tab || 'general'
    }
}
</script>