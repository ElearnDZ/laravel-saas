<template>
    <form class="w-full max-w-sm rounded shadow-md p-6 mx-auto" method="POST" :action="action">
        <div>
            <label for="name" class="form-label">Name</label>

            <div>
                <input id="name" type="text" name="name" v-model="formData.name" required autofocus>
            </div>
        </div>

        <div>
            <label for="email" class="form-label">E-Mail Address</label>

            <div>
                <input id="email" type="email" name="email" v-model="formData.email" required>
            </div>
        </div>

        <div>
            <label for="password" class="form-label">Password</label>

            <div>
                <input id="password" type="password" name="password" v-model="formData.password" required>
            </div>
        </div>

        <div>
            <label for="password-confirm" class="form-label">Confirm Password</label>

            <div>
                <input id="password-confirm" type="password" name="password_confirmation" v-model="formData.password_confirmation" required>
            </div>
        </div>

        <div>
            <label for="plan" class="form-label">Select your plan</label>

            <div>
                <select id="plan" type="password" name="plan" v-model="formData.plan" required>
                    <option value="none" selected>I want a 7-day free trial</option>

                    <option 
                    v-for="plan in plans" 
                    :key="plan.id"
                    :value="plan">
                        {{ plan.pretty_name }}
                    </option>
                </select>
            </div>
        </div>


        <div class="mt-6 flex items-center justify-end">
            <button @click.prevent="triggerStripe" type="submit" class="btn">
                Register {{ formData.plan !== "none" ? "and pay" : "" }}
            </button>
        </div>

        <input type="hidden" name="plan" v-model="formData.plan.id">
        <input type="hidden" name="stripe_token" v-model="chargingToken">
        <input type="hidden" name="city" v-model="formData.billing.city">
        <input type="hidden" name="country" v-model="formData.billing.country">
        <input type="hidden" name="country_code" v-model="formData.billing.countryCode">
        <input type="hidden" name="street" v-model="formData.billing.street">
        <input type="hidden" name="zip" v-model="formData.billing.zip">

        <input type="hidden" name="_token" v-model="csrfToken">
    </form>
</template>

<script>
export default {
    props: ['plans', 'errors', 'old', 'action'],

    methods: {
        triggerStripe() {
            if (this.formData.plan === "none") {
                this.$el.submit()
                return
            }

            this.stripe.open({
                email: this.formData.email,
                description: this.formData.plan.pretty_name,
                amount: this.formData.plan.price
            })
        }
    },

    data() {
        return {
            stripe: null,
            csrfToken: Configuration.csrfToken,
            chargingToken: '',
            formData: {
                name: this.old.name || '',
                email: this.old.email || '',
                password: '',
                password_confirmation: '',
                plan: 'none',
                billing: {
                    city: '',
                    country: '',
                    countryCode: '',
                    street: '',
                    zip: '',
                }
            }
        }
    },

    created() {
        console.log(this.errors);

        this.stripe = StripeCheckout.configure({
            key: Configuration.stripeToken,
            billingAddress: true,
            locale: 'auto',
            name: 'Laravel for SaaS',
            description: 'This is a demo Stripe subscription.',
            image: 'https://stripe.com/img/documentation/checkout/marketplace.png',
            token: (token, args) => {
                this.chargingToken = token.id

                this.formData.billing.city = args.billing_address_city
                this.formData.billing.country = args.billing_address_country
                this.formData.billing.countryCode = args.billing_address_country_code
                this.formData.billing.street = args.billing_address_line1
                this.formData.billing.zip = args.billing_address_zip

                setTimeout(() => this.$el.submit(), 250)
            }
        })
    }
}
</script>
