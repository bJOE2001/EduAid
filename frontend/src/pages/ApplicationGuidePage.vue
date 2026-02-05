<template>
  <q-page class="q-pa-md">
    <div class="row q-col-gutter-md">
      <div class="col-12">
        <div class="text-h4 q-mb-md">Application Guide</div>
      </div>

      <div class="col-12 col-md-8">
        <q-stepper
          v-model="step"
          color="primary"
          animated
          vertical
        >
          <q-step
            :name="1"
            title="Create Account"
            icon="person"
            :done="step > 1"
          >
            <div class="text-body1">
              <p>1. Click on "Register" in the top navigation</p>
              <p>2. Fill in your personal information (Name, Email, Password)</p>
              <p>3. Verify your email address</p>
              <p>4. Complete your applicant profile</p>
            </div>
          </q-step>

          <q-step
            :name="2"
            title="Complete Profile"
            icon="edit"
            :done="step > 2"
          >
            <div class="text-body1">
              <p>1. Go to your Profile page</p>
              <p>2. Fill in all required information:</p>
              <ul>
                <li>Personal details (Name, Birth Date, Gender, Contact)</li>
                <li>Address information</li>
                <li>Academic information (School, Course, Year Level, GWA)</li>
                <li>Family information (Income, Members, Parents/Guardian)</li>
              </ul>
              <p>3. Save your profile</p>
            </div>
          </q-step>

          <q-step
            :name="3"
            title="Browse Scholarships"
            icon="school"
            :done="step > 3"
          >
            <div class="text-body1">
              <p>1. Visit the Scholarships page</p>
              <p>2. Review available scholarship programs</p>
              <p>3. Check eligibility requirements</p>
              <p>4. Read program details and benefits</p>
              <p>5. Note application deadlines</p>
            </div>
          </q-step>

          <q-step
            :name="4"
            title="Submit Application"
            icon="send"
            :done="step > 4"
          >
            <div class="text-body1">
              <p>1. Select a scholarship program</p>
              <p>2. Click "Apply Now"</p>
              <p>3. Upload all required documents:</p>
              <ul>
                <li>Valid ID</li>
                <li>School Registration Form</li>
                <li>Transcript of Records</li>
                <li>Income Certificate</li>
                <li>Other required documents</li>
              </ul>
              <p>4. Review your application</p>
              <p>5. Submit your application</p>
            </div>
          </q-step>

          <q-step
            :name="5"
            title="Track Application"
            icon="track_changes"
          >
            <div class="text-body1">
              <p>1. Go to "My Applications" in your dashboard</p>
              <p>2. View application status:</p>
              <ul>
                <li><strong>Pending:</strong> Application submitted, awaiting review</li>
                <li><strong>Under Review:</strong> Documents being verified</li>
                <li><strong>For Screening:</strong> Application being evaluated</li>
                <li><strong>Approved:</strong> Application accepted!</li>
                <li><strong>Rejected:</strong> Application not approved</li>
              </ul>
              <p>3. Check for notifications and updates</p>
            </div>
          </q-step>
        </q-stepper>
      </div>

      <div class="col-12 col-md-4">
        <q-card>
          <q-card-section>
            <div class="text-h6">Important Reminders</div>
          </q-card-section>
          <q-card-section>
            <q-list>
              <q-item>
                <q-item-section avatar>
                  <q-icon name="info" color="primary" />
                </q-item-section>
                <q-item-section>
                  <q-item-label>Complete Profile</q-item-label>
                  <q-item-label caption>Ensure all profile information is accurate</q-item-label>
                </q-item-section>
              </q-item>
              <q-item>
                <q-item-section avatar>
                  <q-icon name="schedule" color="warning" />
                </q-item-section>
                <q-item-section>
                  <q-item-label>Deadlines</q-item-label>
                  <q-item-label caption>Submit before the application deadline</q-item-label>
                </q-item-section>
              </q-item>
              <q-item>
                <q-item-section avatar>
                  <q-icon name="description" color="positive" />
                </q-item-section>
                <q-item-section>
                  <q-item-label>Documents</q-item-label>
                  <q-item-label caption>Prepare all required documents in advance</q-item-label>
                </q-item-section>
              </q-item>
              <q-item>
                <q-item-section avatar>
                  <q-icon name="email" color="info" />
                </q-item-section>
                <q-item-section>
                  <q-item-label>Notifications</q-item-label>
                  <q-item-label caption>Check your email and dashboard regularly</q-item-label>
                </q-item-section>
              </q-item>
            </q-list>
          </q-card-section>
        </q-card>

        <q-card class="q-mt-md">
          <q-card-section>
            <div class="text-h6">Need Help?</div>
          </q-card-section>
          <q-card-section>
            <p class="text-body2">If you encounter any issues or have questions, please contact our support team:</p>
            <p class="text-body2">
              <strong>Email:</strong> support@eduaid.gov<br>
              <strong>Phone:</strong> (02) 1234-5678<br>
              <strong>Hours:</strong> Monday-Friday, 8AM-5PM
            </p>
          </q-card-section>
        </q-card>
      </div>
    </div>
  </q-page>
</template>

<script>
import { defineComponent, ref } from 'vue'

export default defineComponent({
  name: 'ApplicationGuidePage',
  setup() {
    const step = ref(1)

    return {
      step
    }
  }
})
</script>
