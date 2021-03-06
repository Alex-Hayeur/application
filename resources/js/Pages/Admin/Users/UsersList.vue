<template>
  <div>
    <!-- Manage Application Users -->
    <user-table
      :users="users"
      :paginator="paginator"
    >
      <template v-slot:user="{ user }">
        <div class="flex items-center">

          <button class="cursor-pointer ml-6 text-sm inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150 cursor-pointer ml-6 text-sm focus:outline-none"
                  @click="openUpdateModal(user)">
            Edit
          </button>

          <button class="cursor-pointer ml-6 text-sm inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150 cursor-pointer ml-6 text-sm focus:outline-none"
                  @click="userBeingDeleted = user">
            Delete
          </button>

          <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none"
                  @click="resetModalUser = user">
            Reset link
          </button>

        </div>
      </template>
    </user-table>

    <reset-link-modal :user="resetModalUser" @close="resetModalUser = null" />

    <jet-confirmation-modal :show="userBeingUpdated != null" @close="userBeingUpdated = null">
      <template #title>
        Update User: {{ userBeingUpdated != null && userBeingUpdated.name }}
      </template>

      <template #content>
        <div>
          <jet-label for="name" value="Name"/>
          <jet-input dusk="name" type="text" class="mt-1 block w-full" v-model="updateUserForm.name" autofocus/>
          <jet-input-error :message="updateUserForm.error('name')" class="mt-2"/>
        </div>
        <div>
          <jet-label for="email" value="Email"/>
          <jet-input dusk="email" type="text" class="mt-1 block w-full" v-model="updateUserForm.email"/>
          <jet-input-error :message="updateUserForm.error('email')" class="mt-2"/>
        </div>
        <!-- Permissions -->
        <div class="mt-2 col-span-12" v-if="roles.length > 0">
          <jet-label for="roles" value="Roles"/>

          <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div v-for="role in roles">
              <label class="flex items-center">
                <input :dusk="'check-'+role.id" type="checkbox" class="form-checkbox" :value="role.name"
                       v-model="updateUserForm.roles">
                <span class="ml-2 text-md text-black">{{ role.name }}</span>
                <span class="ml-2 text-sm text-gray-600">{{ role.guard_name }}</span>
              </label>
            </div>
          </div>
        </div>
      </template>

      <template #footer>
        <jet-secondary-button @click.native="userBeingUpdated = null">
          Nevermind
        </jet-secondary-button>

        <jet-button class="ml-2" @click.native="updateUser"
                    :class="{ 'opacity-25': updateUserForm.processing }"
                    :disabled="updateUserForm.processing">
          Update
        </jet-button>
      </template>
    </jet-confirmation-modal>


    <!-- Delete User Confirmation Modal -->
    <jet-confirmation-modal :show="userBeingDeleted" @close="userBeingDeleted = null">
      <template #title>
        Delete User
      </template>

      <template #content>
        Are you sure you would like to delete this user?
      </template>

      <template #footer>
        <jet-secondary-button @click.native="userBeingDeleted = null">
          Nevermind
        </jet-secondary-button>

        <jet-danger-button id="delete" class="ml-2" @click.native="deleteUser"
                           :class="{ 'opacity-25': deleteUserForm.processing }"
                           :disabled="deleteUserForm.processing">
          Delete
        </jet-danger-button>
      </template>
    </jet-confirmation-modal>
  </div>
</template>

<script>
import JetSectionBorder from '@src/Jetstream/SectionBorder'
import JetActionSection from '@src/Jetstream/ActionSection'
import JetConfirmationModal from '@src/Jetstream/ConfirmationModal'
import JetSecondaryButton from '@src/Jetstream/SecondaryButton'
import JetDangerButton from '@src/Jetstream/DangerButton'
import JetButton from '@src/Jetstream/Button'
import JetModal from '@src/Jetstream/Modal'
import Dropdown from "@src/Jetstream/Dropdown";
import JetInput from "@src/Jetstream/Input"
import JetInputError from "@src/Jetstream/InputError"
import JetLabel from "@src/Jetstream/Label"
import XSection from "@src/Components/XSection";
import UserTable from "@src/Components/Tables/UsersTable";
import ResetLinkModal from "@src/Pages/Admin/Users/ResetLinkModal";

export default {
  props: {
    users: {
      type: Array,
      default: function () {
        return []
      },
    },
    roles: {
      type: Array,
      default: function () {
        return []
      },
    },
    paginator: Object
  },

  components: {
    UserTable,
    Dropdown,
    JetSectionBorder,
    JetActionSection,
    JetButton,
    JetDangerButton,
    JetSecondaryButton,
    JetConfirmationModal,
    JetModal,
    JetInput,
    JetLabel,
    JetInputError,
    XSection,
    ResetLinkModal
  },

  data() {
    return {
      deleteUserForm: this.$inertia.form(),
      updateUserForm: this.$inertia.form({
        name: '',
        email: '',
        roles: [],
      }, {
        bag: 'updateUser',
        resetOnSuccess: true,
      }),
      userBeingDeleted: null,
      userBeingUpdated: null,
      resetModalUser: null,
    }
  },

  methods: {
    deleteUser() {
      this.deleteUserForm.delete('/admin/users/' + this.userBeingDeleted.id, {
        preserveScroll: true,
        preserveState: true,
      }).then(() => {
        this.userBeingDeleted = null
      })
    },

    openResetModal(user) {
      this.resetModalUser = user;
    },

    openUpdateModal(user) {
      this.setSelectedRoles(user)
      this.userBeingUpdated = user;
      this.updateUserForm.name = user.name;
      this.updateUserForm.email = user.email;
    },

    updateUser() {
      this.updateUserForm.put('/admin/users/' + this.userBeingUpdated.id, {
        preserveScroll: true,
        preserveState: true,
      }).then(() => {
        if (this.updateUserForm.successful) {
          this.userBeingUpdated = null;
        }
      });
    },

    setSelectedRoles(user) {
      this.updateUserForm.roles = this.mapUserRoles(user.roles)
    },

    mapUserRoles(roles) {
      return roles.map((o) => {
        return o.name;
      });
    }
  }
}
</script>
