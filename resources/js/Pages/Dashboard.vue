<script setup>
import { computed, ref } from 'vue'
import { Head, usePage } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const page = usePage()

const lang = computed(() => page.props.language || {})
const currentLocale = computed(() => page.props.locale || 'en')

const availableLocales = computed(() => {
    return page.props.availableLocales || []
})

const search = ref('')
const selectedCategory = ref('all')

const products = ref([
    {
        id: 1,
        name: 'Diamond Necklace',
        category: 'Necklace',
        price: 125000,
        description: 'Elegant diamond necklace with premium finishing.',
        image: 'https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?auto=format&fit=crop&w=900&q=85',
    },
    {
        id: 2,
        name: 'Gold Bracelet',
        category: 'Bracelet',
        price: 85000,
        description: 'Classic gold bracelet suitable for everyday luxury.',
        image: 'https://images.unsplash.com/photo-1611652022419-a9419f74343d?auto=format&fit=crop&w=900&q=85',
    },
    {
        id: 3,
        name: 'Pearl Earrings',
        category: 'Earrings',
        price: 45000,
        description: 'Beautiful pearl earrings with a timeless design.',
        image: 'https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?auto=format&fit=crop&w=900&q=85',
    },
    {
        id: 4,
        name: 'Ruby Ring',
        category: 'Ring',
        price: 95000,
        description: 'Premium ruby ring with a sophisticated appearance.',
        image: 'https://images.unsplash.com/photo-1605100804763-247f67b3557e?auto=format&fit=crop&w=900&q=85',
    },
    {
        id: 5,
        name: 'Luxury Watch',
        category: 'Watch',
        price: 175000,
        description: 'Luxury watch designed for a modern collection.',
        image: 'https://images.unsplash.com/photo-1523170335258-f5ed11844a49?auto=format&fit=crop&w=900&q=85',
    },
    {
        id: 6,
        name: 'Silver Chain',
        category: 'Chain',
        price: 35000,
        description: 'Minimal silver chain with a modern finish.',
        image: 'https://images.unsplash.com/photo-1599459183200-59c7687a027a?auto=format&fit=crop&w=900&q=85',
    },
])

const categories = computed(() => {
    return [
        'all',
        ...new Set(products.value.map(product => product.category)),
    ]
})

const filteredProducts = computed(() => {
    const query = search.value.trim().toLowerCase()

    return products.value.filter(product => {
        const matchesSearch =
            !query ||
            product.name.toLowerCase().includes(query) ||
            product.category.toLowerCase().includes(query) ||
            product.description.toLowerCase().includes(query)

        const matchesCategory =
            selectedCategory.value === 'all' ||
            selectedCategory.value === product.category

        return matchesSearch && matchesCategory
    })
})

const totalProducts = computed(() => products.value.length)

const totalCategories = computed(() => {
    return new Set(products.value.map(product => product.category)).size
})

const totalValue = computed(() => {
    return products.value.reduce(
        (total, product) => total + product.price,
        0
    )
})

const averagePrice = computed(() => {
    return products.value.length
        ? totalValue.value / products.value.length
        : 0
})

const translate = (key, fallback) => {
    const keys = key.split('.')

    let value = lang.value

    for (const part of keys) {
        if (
            value &&
            typeof value === 'object' &&
            Object.prototype.hasOwnProperty.call(value, part)
        ) {
            value = value[part]
        } else {
            return fallback
        }
    }

    return typeof value === 'string' ? value : fallback
}

const localeMap = {
    en: {
        locale: 'en-IN',
        flag: '🇬🇧',
    },
    gu: {
        locale: 'gu-IN',
        flag: '🇮🇳',
    },
    hi: {
        locale: 'hi-IN',
        flag: '🇮🇳',
    },
    es: {
        locale: 'es-ES',
        flag: '🇪🇸',
    },
    fr: {
        locale: 'fr-FR',
        flag: '🇫🇷',
    },
}

const getLocaleFlag = code => {
    return localeMap[code]?.flag || '🌐'
}

const getLocaleNumberFormat = () => {
    return localeMap[currentLocale.value]?.locale || 'en-IN'
}

const formatCurrency = value => {
    return new Intl.NumberFormat(getLocaleNumberFormat(), {
        style: 'currency',
        currency: 'INR',
        maximumFractionDigits: 0,
    }).format(value)
}

const getLanguageName = locale => {
    return locale.native_name || locale.name || locale.code
}

const resetFilters = () => {
    search.value = ''
    selectedCategory.value = 'all'
}
</script>

<template>
    <Head :title="translate('dashboard.title', 'Dashboard')" />

    <AuthenticatedLayout>

        <template #header>
            <div class="dashboard-header">
                <div>
                    <div class="header-eyebrow">
                        <span class="eyebrow-dot"></span>

                        {{ translate(
                            'dashboard.badge',
                            'Multilingual Product Experience'
                        ) }}
                    </div>

                    <h1 class="header-title">
                        {{ translate('dashboard.title', 'Dashboard') }}
                    </h1>

                    <p class="header-subtitle">
                        {{ translate(
                            'dashboard.subtitle',
                            'Explore our multilingual product collection.'
                        ) }}
                    </p>
                </div>

                <div class="current-language">
                    <span class="language-flag">
                        {{ getLocaleFlag(currentLocale) }}
                    </span>

                    <div>
                        <small>
                            {{ translate(
                                'dashboard.current_language',
                                'Current Language'
                            ) }}
                        </small>

                        <strong>
                            {{ currentLocale.toUpperCase() }}
                        </strong>
                    </div>
                </div>
            </div>
        </template>

        <div class="dashboard-page">

            <!-- HERO -->
            <section class="hero-section">

                <div class="hero-content">

                    <div class="hero-pill">
                        <span>✦</span>

                        {{ translate(
                            'dashboard.welcome_badge',
                            'Premium Collection'
                        ) }}
                    </div>

                    <h2>
                        {{ translate(
                            'dashboard.welcome',
                            'Welcome to the Product Dashboard'
                        ) }}
                    </h2>

                    <p>
                        {{ translate(
                            'dashboard.description',
                            'Browse products and experience the application in your preferred language.'
                        ) }}
                    </p>

                    <div class="hero-actions">
                        <span class="hero-stat">
                            <strong>{{ totalProducts }}</strong>
                            {{ translate(
                                'dashboard.products',
                                'Products'
                            ) }}
                        </span>

                        <span class="hero-divider"></span>

                        <span class="hero-stat">
                            <strong>{{ totalCategories }}</strong>
                            {{ translate(
                                'dashboard.categories',
                                'Categories'
                            ) }}
                        </span>
                    </div>

                </div>

                <div class="hero-decoration">

                    <div class="hero-glow"></div>

                    <div class="hero-ring ring-one"></div>
                    <div class="hero-ring ring-two"></div>

                    <div class="diamond-icon">
                        💎
                    </div>

                </div>

            </section>

            <!-- STATISTICS -->
            <section class="statistics-grid">

                <div class="stat-card">
                    <div class="stat-icon products-icon">
                        🛍️
                    </div>

                    <div class="stat-info">
                        <span>
                            {{ translate(
                                'dashboard.total_products',
                                'Total Products'
                            ) }}
                        </span>

                        <strong>{{ totalProducts }}</strong>

                        <small>
                            {{ translate(
                                'dashboard.active_collection',
                                'Active collection'
                            ) }}
                        </small>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon category-icon">
                        ◈
                    </div>

                    <div class="stat-info">
                        <span>
                            {{ translate(
                                'dashboard.categories',
                                'Categories'
                            ) }}
                        </span>

                        <strong>{{ totalCategories }}</strong>

                        <small>
                            {{ translate(
                                'dashboard.product_categories',
                                'Product categories'
                            ) }}
                        </small>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon value-icon">
                        ₹
                    </div>

                    <div class="stat-info">
                        <span>
                            {{ translate(
                                'dashboard.total_value',
                                'Total Value'
                            ) }}
                        </span>

                        <strong>{{ formatCurrency(totalValue) }}</strong>

                        <small>
                            {{ translate(
                                'dashboard.collection_value',
                                'Collection value'
                            ) }}
                        </small>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon average-icon">
                        ↗
                    </div>

                    <div class="stat-info">
                        <span>
                            {{ translate(
                                'dashboard.average_price',
                                'Average Price'
                            ) }}
                        </span>

                        <strong>{{ formatCurrency(averagePrice) }}</strong>

                        <small>
                            {{ translate(
                                'dashboard.average_product_value',
                                'Average product value'
                            ) }}
                        </small>
                    </div>
                </div>

            </section>

            <!-- SEARCH -->
            <section class="filter-card">

                <div class="filter-heading">

                    <div>
                        <span class="section-kicker">
                            {{ translate(
                                'dashboard.collection',
                                'Collection'
                            ) }}
                        </span>

                        <h3>
                            {{ translate(
                                'dashboard.products',
                                'Products'
                            ) }}
                        </h3>
                    </div>

                    <span class="result-count">
                        {{ filteredProducts.length }}
                        /
                        {{ totalProducts }}
                    </span>

                </div>

                <div class="filter-controls">

                    <div class="search-wrapper">
                        <span class="search-icon">
                            ⌕
                        </span>

                        <input
                            v-model="search"
                            type="text"
                            :placeholder="translate(
                                'dashboard.search_placeholder',
                                'Search by product name, category or description...'
                            )"
                        >

                        <button
                            v-if="search"
                            type="button"
                            class="clear-search"
                            @click="search = ''"
                        >
                            ×
                        </button>
                    </div>

                    <div class="category-wrapper">

                        <span class="category-icon-small">
                            ◈
                        </span>

                        <select v-model="selectedCategory">
                            <option value="all">
                                {{ translate(
                                    'dashboard.all_categories',
                                    'All Categories'
                                ) }}
                            </option>

                            <option
                                v-for="category in categories.filter(
                                    item => item !== 'all'
                                )"
                                :key="category"
                                :value="category"
                            >
                                {{ category }}
                            </option>
                        </select>

                    </div>

                    <button
                        type="button"
                        class="reset-button"
                        @click="resetFilters"
                    >
                        ↻
                        {{ translate(
                            'dashboard.reset',
                            'Reset'
                        ) }}
                    </button>

                </div>

                <div class="filter-footer">

                    <span>
                        <span class="status-dot"></span>

                        {{ translate(
                            'dashboard.showing',
                            'Showing'
                        ) }}

                        <strong>{{ filteredProducts.length }}</strong>

                        {{ translate(
                            'dashboard.of',
                            'of'
                        ) }}

                        <strong>{{ totalProducts }}</strong>

                        {{ translate(
                            'dashboard.products',
                            'products'
                        ) }}
                    </span>

                    <span
                        v-if="search || selectedCategory !== 'all'"
                        class="filtered-label"
                    >
                        Filtered results
                    </span>

                </div>

            </section>

            <!-- PRODUCTS -->
            <section class="products-section">

                <div
                    v-if="filteredProducts.length"
                    class="products-grid"
                >

                    <article
                        v-for="product in filteredProducts"
                        :key="product.id"
                        class="product-card"
                    >

                        <div class="product-image-wrapper">

                            <img
                                :src="product.image"
                                :alt="product.name"
                                class="product-image"
                            >

                            <div class="image-overlay"></div>

                            <span class="product-category">
                                {{ product.category }}
                            </span>

                            <span class="product-number">
                                {{ String(product.id).padStart(2, '0') }}
                            </span>

                        </div>

                        <div class="product-content">

                            <div class="product-top">

                                <div>
                                    <span class="product-label">
                                        {{ product.category }}
                                    </span>

                                    <h4>
                                        {{ product.name }}
                                    </h4>
                                </div>

                                <span class="product-arrow">
                                    ↗
                                </span>

                            </div>

                            <p>
                                {{ product.description }}
                            </p>

                            <div class="product-bottom">

                                <div>
                                    <small>
                                        {{ translate(
                                            'dashboard.price',
                                            'Price'
                                        ) }}
                                    </small>

                                    <strong>
                                        {{ formatCurrency(product.price) }}
                                    </strong>
                                </div>

                                <div class="luxury-mark">
                                    ✦
                                </div>

                            </div>

                        </div>

                    </article>

                </div>

                <!-- EMPTY -->
                <div
                    v-else
                    class="empty-state"
                >
                    <div class="empty-icon">
                        ⌕
                    </div>

                    <h3>
                        {{ translate(
                            'dashboard.no_products',
                            'No products found'
                        ) }}
                    </h3>

                    <p>
                        {{ translate(
                            'dashboard.no_products_description',
                            'Try changing your search or category filter.'
                        ) }}
                    </p>

                    <button
                        type="button"
                        @click="resetFilters"
                    >
                        {{ translate(
                            'dashboard.reset',
                            'Reset Filters'
                        ) }}
                    </button>
                </div>

            </section>

            <!-- LANGUAGE SECTION -->
            <section class="language-section">

                <div class="language-content">

                    <div class="language-copy">

                        <span class="section-kicker">
                            {{ translate(
                                'dashboard.localization',
                                'Localization'
                            ) }}
                        </span>

                        <h3>
                            {{ translate(
                                'dashboard.available_languages',
                                'Available Languages'
                            ) }}
                        </h3>

                        <p>
                            {{ translate(
                                'dashboard.language_description',
                                'Switch languages from the navigation menu.'
                            ) }}
                        </p>

                    </div>

                    <div class="language-list">

                        <div
                            v-for="locale in availableLocales"
                            :key="locale.code"
                            class="language-item"
                            :class="{
                                active:
                                    locale.code === currentLocale
                            }"
                        >

                            <div class="language-item-icon">
                                {{ getLocaleFlag(locale.code) }}
                            </div>

                            <div class="language-item-text">
                                <strong>
                                    {{ getLanguageName(locale) }}
                                </strong>

                                <span>
                                    {{ locale.code.toUpperCase() }}
                                </span>
                            </div>

                            <div
                                v-if="locale.code === currentLocale"
                                class="active-check"
                            >
                                ✓
                            </div>

                        </div>

                    </div>

                </div>

                <div class="language-decoration">
                    <span>EN</span>
                    <span>GU</span>
                    <span>HI</span>
                    <span>ES</span>
                    <span>FR</span>
                </div>

            </section>

            <!-- FOOTER -->
            <footer class="dashboard-footer">

                <span>
                    ✦
                    {{ translate(
                        'dashboard.footer',
                        'Multilingual Product Management'
                    ) }}
                </span>

                <span>
                    {{ currentLocale.toUpperCase() }}
                    ·
                    {{ new Date().getFullYear() }}
                </span>

            </footer>

        </div>

    </AuthenticatedLayout>
</template>

<style scoped>
.dashboard-page {
    min-height: calc(100vh - 80px);
    background:
        radial-gradient(
            circle at 90% 5%,
            rgba(99, 102, 241, 0.07),
            transparent 25%
        ),
        #f7f8fc;
    padding: 30px;
}

/* HEADER */

.dashboard-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 25px;
}

.header-eyebrow {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #6366f1;
    font-size: 12px;
    font-weight: 800;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    margin-bottom: 7px;
}

.eyebrow-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #6366f1;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
}

.header-title {
    margin: 0;
    font-size: 30px;
    font-weight: 800;
    letter-spacing: -0.8px;
    color: #171923;
}

.header-subtitle {
    margin: 5px 0 0;
    color: #73788a;
    font-size: 14px;
}

.current-language {
    display: flex;
    align-items: center;
    gap: 11px;
    background: #fff;
    padding: 10px 15px;
    border: 1px solid #e8eaf0;
    border-radius: 14px;
    box-shadow: 0 5px 20px rgba(30, 35, 60, 0.05);
}

.current-language .language-flag {
    width: 38px;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f4f5ff;
    border-radius: 11px;
    font-size: 20px;
}

.current-language small {
    display: block;
    color: #969aaa;
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: .7px;
}

.current-language strong {
    display: block;
    color: #272b3a;
    font-size: 13px;
}

/* HERO */

.hero-section {
    position: relative;
    min-height: 320px;
    display: flex;
    align-items: center;
    overflow: hidden;
    border-radius: 26px;
    margin-bottom: 25px;
    background:
        linear-gradient(
            120deg,
            #181a2c 0%,
            #25284a 55%,
            #45487d 100%
        );
    box-shadow: 0 18px 45px rgba(32, 35, 65, 0.18);
}

.hero-content {
    position: relative;
    z-index: 3;
    width: 62%;
    padding: 48px;
    color: white;
}

.hero-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 7px 13px;
    border: 1px solid rgba(255,255,255,.16);
    background: rgba(255,255,255,.08);
    backdrop-filter: blur(10px);
    border-radius: 100px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .8px;
    text-transform: uppercase;
}

.hero-pill span {
    color: #c9c6ff;
}

.hero-content h2 {
    max-width: 650px;
    margin: 18px 0 12px;
    font-size: clamp(30px, 4vw, 52px);
    line-height: 1.04;
    font-weight: 800;
    letter-spacing: -1.7px;
}

.hero-content p {
    max-width: 600px;
    margin: 0;
    color: rgba(255,255,255,.67);
    font-size: 15px;
    line-height: 1.7;
}

.hero-actions {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-top: 30px;
}

.hero-stat {
    color: rgba(255,255,255,.58);
    font-size: 12px;
}

.hero-stat strong {
    color: white;
    font-size: 19px;
    margin-right: 5px;
}

.hero-divider {
    width: 1px;
    height: 24px;
    background: rgba(255,255,255,.2);
}

.hero-decoration {
    position: absolute;
    right: 0;
    top: 0;
    width: 42%;
    height: 100%;
}

.hero-glow {
    position: absolute;
    width: 350px;
    height: 350px;
    right: 30px;
    top: -60px;
    border-radius: 50%;
    background: rgba(139, 92, 246, .23);
    filter: blur(45px);
}

.hero-ring {
    position: absolute;
    border: 1px solid rgba(255,255,255,.1);
    border-radius: 50%;
}

.ring-one {
    width: 310px;
    height: 310px;
    right: 45px;
    top: 5px;
}

.ring-two {
    width: 210px;
    height: 210px;
    right: 95px;
    top: 55px;
}

.diamond-icon {
    position: absolute;
    right: 120px;
    top: 88px;
    width: 160px;
    height: 160px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 80px;
    border-radius: 45px;
    background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.12);
    box-shadow: 0 30px 70px rgba(0,0,0,.18);
    transform: rotate(-5deg);
}

/* STATISTICS */

.statistics-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 17px;
    margin-bottom: 25px;
}

.stat-card {
    display: flex;
    align-items: center;
    gap: 15px;
    min-height: 135px;
    padding: 21px;
    background: white;
    border: 1px solid #ebedf3;
    border-radius: 18px;
    box-shadow: 0 8px 25px rgba(34, 38, 65, .045);
    transition: .2s ease;
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(34, 38, 65, .09);
}

.stat-icon {
    width: 51px;
    height: 51px;
    flex: 0 0 51px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 15px;
    font-size: 21px;
    font-weight: 800;
}

.products-icon {
    background: #eef2ff;
}

.category-icon {
    background: #f5edff;
}

.value-icon {
    background: #ecfdf5;
    color: #059669;
}

.average-icon {
    background: #fff7ed;
    color: #ea580c;
}

.stat-info span {
    display: block;
    color: #888d9f;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
}

.stat-info strong {
    display: block;
    margin: 3px 0;
    color: #191c29;
    font-size: 22px;
    font-weight: 800;
}

.stat-info small {
    color: #a2a5b2;
    font-size: 10px;
}

/* FILTER */

.filter-card {
    margin-bottom: 27px;
    padding: 23px;
    background: white;
    border: 1px solid #e9ebf2;
    border-radius: 20px;
    box-shadow: 0 8px 25px rgba(34, 38, 65, .045);
}

.filter-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
}

.section-kicker {
    color: #6366f1;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.filter-heading h3 {
    margin: 3px 0 0;
    color: #1d2030;
    font-size: 20px;
    font-weight: 800;
}

.result-count {
    padding: 7px 12px;
    border-radius: 9px;
    background: #f4f5ff;
    color: #6366f1;
    font-size: 12px;
    font-weight: 800;
}

.filter-controls {
    display: grid;
    grid-template-columns: minmax(250px, 1fr) 230px 120px;
    gap: 10px;
}

.search-wrapper,
.category-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.search-wrapper input,
.category-wrapper select {
    width: 100%;
    height: 47px;
    border: 1px solid #e2e4ec;
    border-radius: 11px;
    outline: none;
    background: #fafbfc;
    color: #292d3c;
    font-size: 13px;
    transition: .2s;
}

.search-wrapper input {
    padding: 0 42px;
}

.category-wrapper select {
    padding: 0 38px;
    appearance: none;
}

.search-wrapper input:focus,
.category-wrapper select:focus {
    border-color: #8b8df5;
    background: white;
    box-shadow: 0 0 0 4px rgba(99,102,241,.08);
}

.search-icon,
.category-icon-small {
    position: absolute;
    z-index: 2;
    left: 15px;
    color: #8b90a2;
    font-size: 20px;
}

.clear-search {
    position: absolute;
    right: 11px;
    width: 25px;
    height: 25px;
    border: 0;
    border-radius: 50%;
    background: #e8eaf0;
    color: #656a7c;
}

.reset-button {
    height: 47px;
    border: 1px solid #e2e4ec;
    border-radius: 11px;
    background: white;
    color: #555a6d;
    font-size: 12px;
    font-weight: 700;
    transition: .2s;
}

.reset-button:hover {
    border-color: #6366f1;
    color: #6366f1;
    background: #f7f7ff;
}

.filter-footer {
    display: flex;
    justify-content: space-between;
    margin-top: 14px;
    color: #9296a7;
    font-size: 11px;
}

.status-dot {
    display: inline-block;
    width: 6px;
    height: 6px;
    margin-right: 5px;
    border-radius: 50%;
    background: #10b981;
}

.filtered-label {
    color: #6366f1;
    font-weight: 700;
}

/* PRODUCTS */

.products-section {
    margin-bottom: 30px;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

.product-card {
    overflow: hidden;
    background: white;
    border: 1px solid #e9ebf2;
    border-radius: 20px;
    box-shadow: 0 8px 25px rgba(34,38,65,.045);
    transition: transform .25s ease, box-shadow .25s ease;
}

.product-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 45px rgba(34,38,65,.13);
}

.product-image-wrapper {
    position: relative;
    height: 245px;
    overflow: hidden;
    background: #ececf1;
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .45s ease;
}

.product-card:hover .product-image {
    transform: scale(1.06);
}

.image-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        to bottom,
        rgba(0,0,0,.12),
        transparent 50%,
        rgba(0,0,0,.25)
    );
}

.product-category {
    position: absolute;
    top: 14px;
    left: 14px;
    padding: 6px 10px;
    border-radius: 8px;
    background: rgba(255,255,255,.91);
    color: #343748;
    font-size: 9px;
    font-weight: 800;
    letter-spacing: .7px;
    text-transform: uppercase;
}

.product-number {
    position: absolute;
    right: 14px;
    bottom: 13px;
    color: rgba(255,255,255,.75);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1px;
}

.product-content {
    padding: 19px;
}

.product-top {
    display: flex;
    justify-content: space-between;
    gap: 15px;
}

.product-label {
    display: block;
    margin-bottom: 3px;
    color: #8c91a1;
    font-size: 9px;
    font-weight: 800;
    letter-spacing: .8px;
    text-transform: uppercase;
}

.product-content h4 {
    margin: 0;
    color: #202330;
    font-size: 17px;
    font-weight: 800;
}

.product-arrow {
    width: 31px;
    height: 31px;
    flex: 0 0 31px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 9px;
    background: #f4f5ff;
    color: #6366f1;
    font-size: 15px;
    transition: .2s;
}

.product-card:hover .product-arrow {
    background: #6366f1;
    color: white;
}

.product-content p {
    min-height: 40px;
    margin: 11px 0 17px;
    color: #85899a;
    font-size: 12px;
    line-height: 1.6;
}

.product-bottom {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    padding-top: 14px;
    border-top: 1px solid #eff0f4;
}

.product-bottom small {
    display: block;
    margin-bottom: 2px;
    color: #9b9fad;
    font-size: 9px;
    text-transform: uppercase;
    letter-spacing: .7px;
}

.product-bottom strong {
    color: #272a39;
    font-size: 17px;
    font-weight: 800;
}

.luxury-mark {
    color: #c0c3d0;
    font-size: 18px;
}

/* EMPTY */

.empty-state {
    padding: 70px 20px;
    text-align: center;
    background: white;
    border: 1px solid #e9ebf2;
    border-radius: 20px;
}

.empty-icon {
    width: 65px;
    height: 65px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    border-radius: 20px;
    background: #f3f4ff;
    color: #6366f1;
    font-size: 27px;
}

.empty-state h3 {
    margin-bottom: 7px;
    color: #252838;
    font-size: 20px;
}

.empty-state p {
    margin-bottom: 18px;
    color: #8b90a0;
    font-size: 13px;
}

.empty-state button {
    padding: 10px 18px;
    border: 0;
    border-radius: 10px;
    background: #6366f1;
    color: white;
    font-size: 12px;
    font-weight: 700;
}

/* LANGUAGES */

.language-section {
    position: relative;
    overflow: hidden;
    margin-top: 28px;
    padding: 28px;
    border: 1px solid #e4e6ef;
    border-radius: 22px;
    background: white;
    box-shadow: 0 8px 25px rgba(34,38,65,.045);
}

.language-content {
    position: relative;
    z-index: 2;
}

.language-copy {
    margin-bottom: 20px;
}

.language-copy h3 {
    margin: 4px 0 6px;
    color: #202330;
    font-size: 22px;
    font-weight: 800;
}

.language-copy p {
    margin: 0;
    color: #8b90a0;
    font-size: 12px;
}

.language-list {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 10px;
}

.language-item {
    position: relative;
    display: flex;
    align-items: center;
    gap: 10px;
    min-height: 67px;
    padding: 10px 12px;
    border: 1px solid #e7e9ef;
    border-radius: 13px;
    background: #fafbfc;
    transition: .2s;
}

.language-item:hover {
    border-color: #b9bbf8;
    background: #f8f8ff;
    transform: translateY(-2px);
}

.language-item.active {
    border-color: #6366f1;
    background: #f5f5ff;
    box-shadow: 0 5px 18px rgba(99,102,241,.09);
}

.language-item-icon {
    width: 37px;
    height: 37px;
    flex: 0 0 37px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    background: white;
    font-size: 18px;
}

.language-item-text {
    min-width: 0;
}

.language-item-text strong {
    display: block;
    overflow: hidden;
    color: #313443;
    font-size: 11px;
    font-weight: 800;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.language-item-text span {
    display: block;
    margin-top: 2px;
    color: #969aaa;
    font-size: 9px;
    font-weight: 700;
    letter-spacing: .8px;
}

.active-check {
    position: absolute;
    right: 8px;
    top: 8px;
    width: 17px;
    height: 17px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: #6366f1;
    color: white;
    font-size: 9px;
    font-weight: 800;
}

.language-decoration {
    position: absolute;
    right: -15px;
    bottom: -35px;
    display: flex;
    gap: 5px;
    transform: rotate(-10deg);
    opacity: .035;
    font-size: 65px;
    font-weight: 900;
}

/* FOOTER */

.dashboard-footer {
    display: flex;
    justify-content: space-between;
    padding: 25px 4px 5px;
    color: #9b9fad;
    font-size: 10px;
}

.dashboard-footer span:first-child {
    font-weight: 700;
}

/* RESPONSIVE */

@media (max-width: 1200px) {
    .statistics-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .products-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .language-list {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 992px) {
    .dashboard-page {
        padding: 20px;
    }

    .hero-content {
        width: 75%;
    }

    .hero-decoration {
        opacity: .45;
    }

    .filter-controls {
        grid-template-columns: 1fr 1fr;
    }

    .reset-button {
        grid-column: span 2;
    }
}

@media (max-width: 768px) {
    .dashboard-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .current-language {
        width: 100%;
    }

    .hero-section {
        min-height: 390px;
    }

    .hero-content {
        width: 100%;
        padding: 30px;
    }

    .hero-decoration {
        display: none;
    }

    .statistics-grid {
        grid-template-columns: 1fr;
    }

    .products-grid {
        grid-template-columns: 1fr;
    }

    .filter-controls {
        grid-template-columns: 1fr;
    }

    .reset-button {
        grid-column: auto;
    }

    .language-list {
        grid-template-columns: 1fr 1fr;
    }

    .dashboard-footer {
        flex-direction: column;
        gap: 8px;
    }
}

@media (max-width: 480px) {
    .dashboard-page {
        padding: 14px;
    }

    .hero-content h2 {
        font-size: 31px;
    }

    .language-list {
        grid-template-columns: 1fr;
    }

    .filter-card,
    .language-section {
        padding: 17px;
    }
}
</style>
