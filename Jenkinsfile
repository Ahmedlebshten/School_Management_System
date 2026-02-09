pipeline {
  agent any

  environment {
    IMAGE_NAME  = "ahmedlebshten/school_management_system"
    CD_REPO     = "https://github.com/Ahmedlebshten/School_Management_System_CD.git"
    DEPLOY_FILE = "school/deployment.yaml"
  }

  stages {

    stage('Checkout Source Code') {
      steps {
        checkout scm
      }
    }

    stage('Generate Image Tag') {
      steps {
        script {
          env.IMAGE_TAG = env.BUILD_NUMBER
          echo "Image tag: ${IMAGE_TAG}"
        }
      }
    }

    stage('Clone CD Repo') {
      steps {
        withCredentials([
          usernamePassword(
            credentialsId: 'github-credentials',
            usernameVariable: 'GIT_USER',
            passwordVariable: 'GIT_PASS'
          )
        ]) {
          sh '''
            rm -rf cd-repo
            git clone https://${GIT_USER}:${GIT_PASS}@github.com/Ahmedlebshten/School_Management_System_CD.git cd-repo
          '''
        }
      }
    }

    stage('Build Docker Image') {
      steps {
        sh "docker build -t ${IMAGE_NAME}:${IMAGE_TAG} ."
      }
    }

    stage('Docker Login') {
      steps {
        withCredentials([
          usernamePassword(
            credentialsId: 'dockerhub-credentials',
            usernameVariable: 'DOCKER_USER',
            passwordVariable: 'DOCKER_PASS'
          )
        ]) {
          sh 'echo $DOCKER_PASS | docker login -u $DOCKER_USER --password-stdin'
        }
      }
    }

    stage('Push Docker Image') {
      steps {
        sh "docker push ${IMAGE_NAME}:${IMAGE_TAG}"
      }
    }

    stage('Update CD Repo (bump image tag)') {
      steps {
        sh '''
          cd cd-repo
          test -f ${DEPLOY_FILE} 
          sed -i "s|image: ${IMAGE_NAME}:.*|image: ${IMAGE_NAME}:${IMAGE_TAG}|g" ${DEPLOY_FILE}

          git config user.email "jenkins@ci.local"
          git config user.name "jenkins"

          if git diff --quiet; then
            echo "No changes to commit, image tag is already up to date"
          else

          git add ${DEPLOY_FILE}
          git commit -m "ci: bump image to ${IMAGE_TAG}"
          git push origin HEAD
          fi
        '''
      }
    }
  }

  post {
    success {
      echo "✅ Image ${IMAGE_NAME}:${IMAGE_TAG} pushed and CD repo updated"
    }
    failure {
      echo "❌ CI failed"
    }
  }
}
