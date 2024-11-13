stage('ZAP Analysis') {
    steps {
        script {
            docker.image('zaproxy/zap-stable').inside('-v /zap/wrk:/zap/wrk --network bridge') {
                sh '''
                    # Start ZAP in daemon mode
                    zap.sh -daemon -host 0.0.0.0 -port 8090 -config api.disablekey=true &
                    # Wait for ZAP to be fully ready
                    timeout=300
                    while ! curl -s http://127.0.0.1:8090/OTHER/core/other/htmlreport/; do
                        sleep 5
                        timeout=$((timeout - 5))
                        if [ $timeout -le 0 ]; then
                            echo "ZAP did not start in time"
                            exit 1
                        fi
                    done
                    # Run the full scan
                    zap-full-scan.py -t http://10.30.212.72 -r /zap/wrk/zap_report.html --autooff -I
                    # Shutdown ZAP
                    zap.sh -cmd -shutdown
                '''
            }
        }
        // Copy the ZAP report back to Jenkins workspace
        stage('Copy ZAP Report') {
            steps {
                sh 'cp /zap/wrk/zap_report.html ${WORKSPACE}'
            }
        }
        // Publish the ZAP report
        publishHTML(target: [
            reportDir: "${env.WORKSPACE}",
            reportFiles: 'zap_report.html',
            reportName: 'Reporte ZAP'
        ])
    }
}
