pipeline {
  agent any

  stages {

    stage('Checkout Source Code') {
      steps {
        checkout scm
      }
    }

    stage('CI Trigger Test') {
      steps {
        echo "CI Pipeline triggered successfully 🚀"
        sh 'ls -la'
      }
    }

  }

  post {
    success {
      echo "CI finished successfully ✅"
    }
    failure {
      echo "CI failed ❌"
    }
  }
}
